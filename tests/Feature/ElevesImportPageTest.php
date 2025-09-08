<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ElevesImportPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the import page displays form elements and buttons correctly for authenticated users.
     */
    public function testImportPageDisplaysFormElementsAndButtons(): void
    {
        // Create and authenticate a user
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('eleves.import.form'));
        $response->assertStatus(200);

        // Check page header
        $response->assertSee('Importer des élèves');

        // Check file input
        $response->assertSee('type="file" name="file"', false);

        // Check import buttons and links
        $response->assertSee('Importer avec aperçu');
        $response->assertSee('id="import-direct-btn"', false);
        $response->assertSee('Modèle Excel');
        $response->assertSee('Exporter Excel');
    }
}
