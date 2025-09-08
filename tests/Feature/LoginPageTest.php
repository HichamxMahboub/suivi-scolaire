<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginPageTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginPageDisplaysEmailAndPasswordFieldsAndButtonAndLogo(): void
    {
        // Run migrations and seed admin
        $this->seed();

        $response = $this->get('/login');

        $response->assertStatus(200);

        // Check presence of form fields
        // Check presence of form fields using raw HTML assertion
        $response->assertSeeHtml('name="email"');
        $response->assertSeeHtml('name="password"');

        // Check login button text
        $response->assertSee('Se connecter');

        // Check logo image tag
        // Check logo image tag using raw HTML assertion
        $response->assertSeeHtml('<img src="'.asset('logo-ecole.png').'"');
    }
}
