<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Classe;
use App\Models\Eleve;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClasseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    /** @test */
    public function it_can_display_classes_index()
    {
        $response = $this->actingAs($this->user)->get('/classes');
        $response->assertStatus(200);
        $response->assertViewIs('classes.index');
    }

    /** @test */
    public function it_can_create_classe()
    {
        $classeData = [
            'nom' => '1ère A',
            'niveau' => '1AC',
            'annee_scolaire' => '2024-2025',
            'description' => 'Classe de première année collège',
            'professeur_principal' => 'M. Dupont',
            'effectif_max' => 35,
            'active' => true,
        ];

        $response = $this->actingAs($this->user)->post('/classes', $classeData);
        $response->assertRedirect('/classes');
        $this->assertDatabaseHas('classes', ['nom' => '1ère A']);
    }

    /** @test */
    public function it_can_update_classe()
    {
        $classe = Classe::factory()->create();

        $updateData = [
            'nom' => '1ère B',
            'niveau' => '1AC',
            'annee_scolaire' => '2024-2025',
            'effectif_max' => 40,
        ];

        $response = $this->actingAs($this->user)->put("/classes/{$classe->id}", $updateData);
        $response->assertRedirect('/classes');
        $this->assertDatabaseHas('classes', ['nom' => '1ère B']);
    }

    /** @test */
    public function it_can_delete_classe()
    {
        $classe = Classe::factory()->create();

        $response = $this->actingAs($this->user)->delete("/classes/{$classe->id}");
        $response->assertRedirect('/classes');
        $this->assertDatabaseMissing('classes', ['id' => $classe->id]);
    }

    /** @test */
    public function it_cannot_delete_classe_with_eleves()
    {
        $classe = Classe::factory()->create();
        $eleve = Eleve::factory()->create(['classe_id' => $classe->id]);

        $response = $this->actingAs($this->user)->delete("/classes/{$classe->id}");
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('classes', ['id' => $classe->id]);
    }

    /** @test */
    public function it_can_assign_eleves_to_classe()
    {
        $classe = Classe::factory()->create(['effectif_max' => 5]);
        $eleves = Eleve::factory()->count(3)->create(['classe_id' => null]);

        $response = $this->actingAs($this->user)->post("/classes/{$classe->id}/assigner-eleves", [
            'eleves' => $eleves->pluck('id')->toArray()
        ]);

        $response->assertRedirect();
        $this->assertEquals(3, $classe->fresh()->eleves()->count());
    }

    /** @test */
    public function it_cannot_assign_eleves_beyond_capacity()
    {
        $classe = Classe::factory()->create(['effectif_max' => 2]);
        $eleves = Eleve::factory()->count(3)->create(['classe_id' => null]);

        $response = $this->actingAs($this->user)->post("/classes/{$classe->id}/assigner-eleves", [
            'eleves' => $eleves->pluck('id')->toArray()
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /** @test */
    public function it_can_remove_eleves_from_classe()
    {
        $classe = Classe::factory()->create();
        $eleves = Eleve::factory()->count(3)->create(['classe_id' => $classe->id]);

        $response = $this->actingAs($this->user)->post("/classes/{$classe->id}/retirer-eleves", [
            'eleves' => $eleves->pluck('id')->toArray()
        ]);

        $response->assertRedirect();
        $this->assertEquals(0, $classe->fresh()->eleves()->count());
    }

    /** @test */
    public function it_validates_required_fields_when_creating_classe()
    {
        $response = $this->actingAs($this->user)->post('/classes', []);
        $response->assertSessionHasErrors(['nom', 'niveau', 'annee_scolaire', 'effectif_max']);
    }

    /** @test */
    public function it_validates_effectif_max_range()
    {
        $response = $this->actingAs($this->user)->post('/classes', [
            'nom' => 'Test',
            'niveau' => '1AC',
            'annee_scolaire' => '2024-2025',
            'effectif_max' => 0, // Invalid
        ]);
        
        $response->assertSessionHasErrors(['effectif_max']);
    }
} 