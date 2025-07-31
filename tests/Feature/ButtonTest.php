<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ButtonTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function dashboard_navigation_buttons_are_visible_and_work()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Tableau de bord');
        $response->assertSee('Élèves');
        $response->assertSee('Classes');
        $response->assertSee('Enseignants');
        $response->assertSee('Messages');
    }

    /** @test */
    public function settings_button_opens_modal()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('id="settings-btn"', false);
        $response->assertSee('id="settings-modal"', false);
    }

    /** @test */
    public function can_access_create_eleve_button()
    {
        $response = $this->actingAs($this->admin)->get('/eleves');
        $response->assertStatus(200);
        $response->assertSee('Ajouter un élève');
    }

    /** @test */
    public function can_access_create_classe_button()
    {
        $response = $this->actingAs($this->admin)->get('/classes');
        $response->assertStatus(200);
        $response->assertSee('Ajouter une classe');
    }

    /** @test */
    public function can_access_create_encadrant_button()
    {
        $response = $this->actingAs($this->admin)->get('/encadrants');
        $response->assertStatus(200);
        $response->assertSee('Ajouter un encadrant');
    }

    /** @test */
    public function can_access_create_message_button()
    {
        $response = $this->actingAs($this->admin)->get('/messages');
        $response->assertStatus(200);
        $response->assertSee('Nouveau message');
    }

    /** @test */
    public function can_access_profile_and_logout_buttons()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Profile');
        $response->assertSee('Déconnexion');
    }
} 