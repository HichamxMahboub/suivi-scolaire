<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function it_can_display_users_index()
    {
        $response = $this->actingAs($this->admin)->get('/users');
        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    /** @test */
    public function it_can_create_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'teacher',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->admin)->post('/users', $userData);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    /** @test */
    public function it_can_update_user()
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'role' => 'admin',
        ];

        $response = $this->actingAs($this->admin)->put("/users/{$user->id}", $updateData);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Jane Doe']);
    }

    /** @test */
    public function it_can_update_user_password()
    {
        $user = User::factory()->create(['role' => 'teacher']);

        $updateData = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => 'teacher',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];

        $response = $this->actingAs($this->admin)->put("/users/{$user->id}", $updateData);
        $response->assertRedirect('/users');
        
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }

    /** @test */
    public function it_can_delete_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/users/{$user->id}");
        $response->assertRedirect('/users');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_cannot_delete_own_account()
    {
        $response = $this->actingAs($this->admin)->delete("/users/{$this->admin->id}");
        $response->assertRedirect('/users');
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating()
    {
        $response = $this->actingAs($this->admin)->post('/users', []);
        $response->assertSessionHasErrors(['name', 'email', 'role', 'password']);
    }

    /** @test */
    public function it_validates_email_uniqueness()
    {
        $existingUser = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'Test User',
            'email' => 'test@example.com', // Duplicate email
            'role' => 'teacher',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_validates_password_confirmation()
    {
        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'teacher',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function it_validates_role_values()
    {
        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'invalid_role',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['role']);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $response = $this->actingAs($this->admin)->post('/users', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'role' => 'teacher',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function it_can_display_user_details()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($this->admin)->get("/users/{$user->id}");
        $response->assertStatus(200);
        $response->assertViewIs('users.show');
    }

    /** @test */
    public function it_can_display_user_edit_form()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($this->admin)->get("/users/{$user->id}/edit");
        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
    }

    /** @test */
    public function it_can_display_user_create_form()
    {
        $response = $this->actingAs($this->admin)->get('/users/create');
        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    /** @test */
    public function it_validates_email_uniqueness_on_update()
    {
        $user1 = User::factory()->create(['email' => 'user1@example.com']);
        $user2 = User::factory()->create(['email' => 'user2@example.com']);

        $response = $this->actingAs($this->admin)->put("/users/{$user2->id}", [
            'name' => $user2->name,
            'email' => 'user1@example.com', // Duplicate email
            'role' => $user2->role,
        ]);

        $response->assertSessionHasErrors(['email']);
    }
} 