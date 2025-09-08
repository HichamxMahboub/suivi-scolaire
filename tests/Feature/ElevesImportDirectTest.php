<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Eleve;

class ElevesImportDirectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test direct import of a valid CSV file via AJAX returns success and creates Eleve record.
     */
    public function testDirectImportWithValidCsv(): void
    {
        // Fake storage for file uploads
        Storage::fake('local');

        // Create and authenticate a user
        $user = User::factory()->create();

        // Prepare CSV content (headers and one row)
        $csvContent = "nom,prenom\nDoe,John";

        // Create fake uploaded file
        $file = UploadedFile::fake()->createWithContent('eleves.csv', $csvContent);

        // Perform AJAX POST
        // Send as multipart/form-data with Accept header for JSON
        $response = $this->actingAs($user)
            ->post(route('eleves.import.direct'), [
                'file' => $file,
            ], ['Accept' => 'application/json']);

        // Assert JSON success and imported count
        $response->assertStatus(200)
                 ->assertJson([
                     'success'  => true,
                     'imported' => 1,
                 ]);

        // Assert Eleve record exists in database
        $this->assertDatabaseHas('eleves', [
            'nom'    => 'Doe',
            'prenom' => 'John',
        ]);
    }

    /**
     * Test direct import with invalid file type returns validation error.
     */
    public function testDirectImportWithInvalidFileType(): void
    {
        // Create and authenticate a user
        $user = User::factory()->create();

        // Create fake uploaded file with wrong extension
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        // Send invalid file as multipart/form-data
        $response = $this->actingAs($user)
            ->post(route('eleves.import.direct'), [
                'file' => $file,
            ], ['Accept' => 'application/json']);

        // Expect validation error
        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['file']);
    }
}
