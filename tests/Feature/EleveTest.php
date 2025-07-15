<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class EleveTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function it_can_display_eleves_index()
    {
        $response = $this->actingAs($this->user)->get('/eleves');
        $response->assertStatus(200);
        $response->assertViewIs('eleves.index');
    }

    /** @test */
    public function it_can_create_eleve()
    {
        $classe = Classe::factory()->create();
        
        $eleveData = [
            'numero_matricule' => 'MAT001',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'niveau_scolaire' => '1AC',
            'date_naissance' => '2010-05-15',
            'annee_entree' => 2024,
            'adresse' => '123 Rue de la Paix',
            'educateur_responsable' => 'M. Martin',
            'classe_id' => $classe->id,
        ];

        $response = $this->actingAs($this->user)->post('/eleves', $eleveData);
        $response->assertRedirect('/eleves');
        $this->assertDatabaseHas('eleves', ['numero_matricule' => 'MAT001']);
    }

    /** @test */
    public function it_can_update_eleve()
    {
        $eleve = Eleve::factory()->create();
        $classe = Classe::factory()->create();

        $updateData = [
            'numero_matricule' => 'MAT002',
            'nom' => 'Martin',
            'prenom' => 'Pierre',
            'niveau_scolaire' => '2AC',
            'classe_id' => $classe->id,
        ];

        $response = $this->actingAs($this->user)->put("/eleves/{$eleve->id}", $updateData);
        $response->assertRedirect('/eleves');
        $this->assertDatabaseHas('eleves', ['numero_matricule' => 'MAT002']);
    }

    /** @test */
    public function it_can_delete_eleve()
    {
        $eleve = Eleve::factory()->create();

        $response = $this->actingAs($this->user)->delete("/eleves/{$eleve->id}");
        $response->assertRedirect('/eleves');
        $this->assertDatabaseMissing('eleves', ['id' => $eleve->id]);
    }

    /** @test */
    public function it_can_display_eleve_stats()
    {
        $response = $this->actingAs($this->user)->get('/eleves/stats');
        $response->assertStatus(200);
        $response->assertViewIs('eleves.stats');
    }

    /** @test */
    public function it_can_export_eleves_to_excel()
    {
        $response = $this->actingAs($this->user)->get('/eleves/export/excel');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function it_can_export_eleves_to_pdf()
    {
        $response = $this->actingAs($this->user)->get('/eleves/export/pdf');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function it_can_display_import_form()
    {
        $response = $this->actingAs($this->user)->get('/eleves/import');
        $response->assertStatus(200);
        $response->assertViewIs('eleves.import');
    }

    /** @test */
    public function it_validates_required_fields_when_creating_eleve()
    {
        $response = $this->actingAs($this->user)->post('/eleves', []);
        $response->assertSessionHasErrors(['nom', 'prenom']);
    }

    /** @test */
    public function it_validates_unique_numero_matricule()
    {
        $eleve = Eleve::factory()->create(['numero_matricule' => 'MAT001']);
        
        $response = $this->actingAs($this->user)->post('/eleves', [
            'numero_matricule' => 'MAT001',
            'nom' => 'Test',
            'prenom' => 'User',
        ]);
        
        $response->assertSessionHasErrors(['numero_matricule']);
    }
} 