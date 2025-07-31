<?php

namespace App\Imports;

use App\Models\Note;
use App\Models\Eleve;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotesImport implements ToModel, WithHeadingRow
{

<?php

namespace App\Imports;

use App\Models\Note;
use App\Models\Eleve;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NotesImport implements ToModel, WithHeadingRow
{
    protected $importResults = [
        'success' => 0,
        'errors' => 0,
        'skipped' => 0,
        'details' => []
    ];

    /**
     * Transforme chaque ligne en modèle Note
     */
    public function model(array $row)
    {
        // Ignorer les lignes vides ou sans ID élève
        if (empty($row['id_eleve']) || empty($row['matiere']) || empty($row['note'])) {
            $this->importResults['skipped']++;
            return null;
        }

        try {
            // Vérifier que l'élève existe
            $eleve = Eleve::find($row['id_eleve']);
            if (!$eleve) {
                $this->importResults['errors']++;
                return null;
            }

            // Trouver l'enseignant connecté ou le premier enseignant disponible
            $enseignant = User::where('role', 'enseignant')->first();

            // Créer la note
            $note = Note::create([
                'eleve_id' => $row['id_eleve'],
                'matiere' => $row['matiere'],
                'type_evaluation' => $row['type_evaluation'] ?? 'Contrôle Continu',
                'note' => (float) $row['note'],
                'note_sur' => (float) ($row['note_sur'] ?? 20),
                'date_evaluation' => $this->parseDate($row['date_evaluation']),
                'semestre' => $row['semestre'] ?? 'S2',
                'commentaire' => $row['commentaire'] ?? null,
                'enseignant_id' => $enseignant ? $enseignant->id : null,
            ]);

            $this->importResults['success']++;
            return $note;

        } catch (\Exception $e) {
            $this->importResults['errors']++;
            return null;
        }
    }

    /**
     * Parse les différents formats de date
     */
    protected function parseDate($date)
    {
        if (empty($date)) {
            return now()->format('Y-m-d');
        }

        // Si c'est déjà au bon format
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return $date;
        }

        // Essayer d'autres formats
        $formats = ['d/m/Y', 'd-m-Y', 'Y/m/d', 'm/d/Y'];
        
        foreach ($formats as $format) {
            $parsed = \DateTime::createFromFormat($format, $date);
            if ($parsed && $parsed->format($format) === $date) {
                return $parsed->format('Y-m-d');
            }
        }

        // Si aucun format ne fonctionne, utiliser la date actuelle
        return now()->format('Y-m-d');
    }

    /**
     * Retourne les résultats de l'importation
     */
    public function getImportResults()
    {
        return $this->importResults;
    }

    /**
     * Définit les en-têtes attendues
     */
    public function headingRow(): int
    {
        return 1;
    }
}
}
