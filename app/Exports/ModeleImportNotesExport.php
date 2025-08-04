<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use App\Models\Eleve;

class ModeleImportNotesExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $classe_id;

    public function __construct($classe_id = null)
    {
        $this->classe_id = $classe_id;
    }

    /**
     * Retourne les données pour le modèle d'import
     */
    public function array(): array
    {
        $data = [];
        
        // Récupérer les élèves selon la classe sélectionnée
        $eleves = $this->classe_id 
            ? Eleve::where('classe_id', $this->classe_id)->orderBy('nom')->orderBy('prenom')->get()
            : Eleve::orderBy('nom')->orderBy('prenom')->take(5)->get(); // Limite à 5 exemples si aucune classe

        // Ajouter quelques lignes d'exemple
        foreach ($eleves as $index => $eleve) {
            $data[] = [
                $eleve->id,
                $eleve->nom,
                $eleve->prenom,
                $eleve->classe ? $eleve->classe->nom : '',
                'Mathématiques', // Exemple de matière
                'Devoir Surveillé', // Exemple de type
                $index === 0 ? '15.5' : '', // Note exemple pour le premier élève
                '20', // Note sur
                '2025-01-27', // Date d'évaluation
                'S2', // Semestre
                $index === 0 ? 'Bon travail' : '', // Commentaire exemple
            ];
        }

        // Ajouter quelques lignes vides pour permettre l'ajout
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                '', // ID élève (à remplir)
                '', // Nom (sera rempli automatiquement)
                '', // Prénom (sera rempli automatiquement)
                '', // Classe (sera remplie automatiquement)
                '', // Matière
                '', // Type évaluation
                '', // Note
                '20', // Note sur (valeur par défaut)
                '', // Date évaluation
                'S2', // Semestre (valeur par défaut)
                '', // Commentaire
            ];
        }

        return $data;
    }

    /**
     * Définit les en-têtes des colonnes
     */
    public function headings(): array
    {
        return [
            'ID Élève*',
            'Nom (auto)',
            'Prénom (auto)',
            'Classe (auto)',
            'Matière*',
            'Type Évaluation*',
            'Note*',
            'Note Sur*',
            'Date Évaluation*',
            'Semestre*',
            'Commentaire',
        ];
    }

    /**
     * Définit les styles du fichier Excel
     */
    public function styles(Worksheet $sheet)
    {
        // Style de l'en-tête
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '3B82F6'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style des colonnes automatiques (lecture seule)
        $sheet->getStyle('B:D')->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F3F4F6'],
            ],
        ]);

        // Ajouter des commentaires explicatifs
        $sheet->setCellValue('A50', 'INSTRUCTIONS D\'UTILISATION:');
        $sheet->setCellValue('A51', '1. Remplissez l\'ID Élève (obligatoire) - les autres colonnes se rempliront automatiquement');
        $sheet->setCellValue('A52', '2. Choisissez la matière parmi: Mathématiques, Français, Arabe, Anglais, Sciences Physiques, SVT, Histoire-Géographie, Éducation Islamique');
        $sheet->setCellValue('A53', '3. Type d\'évaluation: Contrôle Continu, Devoir Surveillé, Composition, Examen, Oral');
        $sheet->setCellValue('A54', '4. Note: Valeur numérique (ex: 15.5)');
        $sheet->setCellValue('A55', '5. Date au format: AAAA-MM-JJ (ex: 2025-01-27)');
        $sheet->setCellValue('A56', '6. Semestre: S1 ou S2');
        $sheet->setCellValue('A57', '7. Les colonnes marquées (*) sont obligatoires');
        $sheet->setCellValue('A58', '8. Les colonnes grises se remplissent automatiquement');

        // Style des instructions
        $sheet->getStyle('A50:A58')->applyFromArray([
            'font' => [
                'bold' => false,
                'size' => 10,
                'color' => ['rgb' => '6B7280'],
            ],
        ]);

        $sheet->getStyle('A50')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '1F2937'],
            ],
        ]);

        return $sheet;
    }

    /**
     * Définit les largeurs des colonnes
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12,  // ID Élève
            'B' => 20,  // Nom
            'C' => 20,  // Prénom
            'D' => 15,  // Classe
            'E' => 25,  // Matière
            'F' => 20,  // Type Évaluation
            'G' => 10,  // Note
            'H' => 12,  // Note Sur
            'I' => 18,  // Date Évaluation
            'J' => 12,  // Semestre
            'K' => 30,  // Commentaire
        ];
    }
}
