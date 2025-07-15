<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Message;
use App\Models\Enseignant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SystemHealthTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function dashboard_is_accessible()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }

    /** @test */
    public function eleves_module_works()
    {
        // Test index
        $response = $this->actingAs($this->admin)->get('/eleves');
        $response->assertStatus(200);
        $response->assertViewIs('eleves.index');

        // Test create form
        $response = $this->actingAs($this->admin)->get('/eleves/create');
        $response->assertStatus(200);
        $response->assertViewIs('eleves.create');

        // Test stats
        $response = $this->actingAs($this->admin)->get('/eleves/stats');
        $response->assertStatus(200);
        $response->assertViewIs('eleves.stats');
    }

    /** @test */
    public function classes_module_works()
    {
        // Test index
        $response = $this->actingAs($this->admin)->get('/classes');
        $response->assertStatus(200);
        $response->assertViewIs('classes.index');

        // Test create form
        $response = $this->actingAs($this->admin)->get('/classes/create');
        $response->assertStatus(200);
        $response->assertViewIs('classes.create');
    }

    /** @test */
    public function messages_module_works()
    {
        // Test index
        $response = $this->actingAs($this->admin)->get('/messages');
        $response->assertStatus(200);
        $response->assertViewIs('messages.index');

        // Test create form
        $response = $this->actingAs($this->admin)->get('/messages/create');
        $response->assertStatus(200);
        $response->assertViewIs('messages.create');

        // Test sent messages
        $response = $this->actingAs($this->admin)->get('/messages/sent');
        $response->assertStatus(200);
        $response->assertViewIs('messages.sent');
    }

    /** @test */
    public function users_module_works()
    {
        // Test index
        $response = $this->actingAs($this->admin)->get('/users');
        $response->assertStatus(200);
        $response->assertViewIs('users.index');

        // Test create form
        $response = $this->actingAs($this->admin)->get('/users/create');
        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    /** @test */
    public function encadrant_module_works()
    {
        // Test index
        $response = $this->actingAs($this->admin)->get('/encadrant');
        $response->assertStatus(200);
        $response->assertViewIs('encadrant.index');

        // Test create form
        $response = $this->actingAs($this->admin)->get('/encadrant/create');
        $response->assertStatus(200);
        $response->assertViewIs('encadrant.create');
    }

    /** @test */
    public function can_create_eleve_with_valid_data()
    {
        $classe = Classe::create([
            'nom' => 'Test Classe',
            'niveau' => '1AC',
            'annee_scolaire' => '2024-2025',
            'effectif_max' => 30,
        ]);

        $eleveData = [
            'numero_matricule' => 'MAT001',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'niveau_scolaire' => '1AC',
            'classe_id' => $classe->id,
        ];

        $response = $this->actingAs($this->admin)->post('/eleves', $eleveData);
        $response->assertRedirect('/eleves');
        $this->assertDatabaseHas('eleves', ['numero_matricule' => 'MAT001']);
    }

    /** @test */
    public function can_create_classe_with_valid_data()
    {
        $classeData = [
            'nom' => '1ère A',
            'niveau' => '1AC',
            'annee_scolaire' => '2024-2025',
            'effectif_max' => 35,
        ];

        $response = $this->actingAs($this->admin)->post('/classes', $classeData);
        $response->assertRedirect('/classes');
        $this->assertDatabaseHas('classes', ['nom' => '1ère A']);
    }

    /** @test */
    public function can_create_user_with_valid_data()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'teacher',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->actingAs($this->admin)->post('/users', $userData);
        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function can_create_encadrant_with_valid_data()
    {
        $encadrantData = [
            'nom' => 'Martin',
            'prenom' => 'Pierre',
            'email' => 'martin@example.com',
            'matricule' => 'ENS001',
        ];

        $response = $this->actingAs($this->admin)->post('/encadrant', $encadrantData);
        $response->assertRedirect('/encadrant');
        $this->assertDatabaseHas('encadrant', ['matricule' => 'ENS001']);
    }

    /** @test */
    public function can_send_message_with_valid_data()
    {
        $recipient = User::create([
            'name' => 'Recipient',
            'email' => 'recipient@test.com',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        $messageData = [
            'recipient_id' => $recipient->id,
            'subject' => 'Test Message',
            'content' => 'This is a test message.',
            'priority' => 'normal',
            'type' => 'general',
        ];

        $response = $this->actingAs($this->admin)->post('/messages', $messageData);
        $response->assertRedirect('/messages');
        $this->assertDatabaseHas('messages', ['subject' => 'Test Message']);
    }

    /** @test */
    public function language_switching_works()
    {
        $response = $this->actingAs($this->admin)->post('/lang/fr');
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'locale' => 'fr']);

        $response = $this->actingAs($this->admin)->post('/lang/ar');
        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'locale' => 'ar']);
    }

    /** @test */
    public function export_functionality_works()
    {
        $response = $this->actingAs($this->admin)->get('/eleves/export/excel');
        $response->assertStatus(200);

        $response = $this->actingAs($this->admin)->get('/eleves/export/pdf');
        $response->assertStatus(200);
    }

    /** @test */
    public function import_functionality_works()
    {
        $response = $this->actingAs($this->admin)->get('/eleves/import');
        $response->assertStatus(200);
        $response->assertViewIs('eleves.import');
    }
} 