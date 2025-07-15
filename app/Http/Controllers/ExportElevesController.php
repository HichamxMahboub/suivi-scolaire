<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Http\Response;

class ExportElevesController extends Controller
{
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Logo (public/logo.png)
        $logoPath = public_path('logo.png');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setPath($logoPath);
            $drawing->setHeight(60);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }

        // Titre fusionné
        $sheet->mergeCells('B1:H1');
        $sheet->setCellValue('B1', "Etat global des bénéficiaires du complexe socio-éducatif - Année scolaire");
        $sheet->getStyle('B1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFB74D'],
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(40);

        // En-têtes
        $headers = [
            'N°',
            'Niveau scolaire',
            'Nom',
            'Prénom',
            "Année d'entrée",
            'Date de naissance',
            "n° d'immatriculation",
            'Éducateur responsable',
            'Adresse',
            'Email',
            'Téléphone parent',
            'Remarques',
        ];
        $sheet->fromArray($headers, null, 'A3');
        $sheet->getStyle('A3:L3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '333333'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E0E0E0'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '888888'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Données élèves
        $eleves = Eleve::all();
        $rows = [];
        $i = 1;
        foreach ($eleves as $eleve) {
            $rows[] = [
                $i++,
                $eleve->niveau_scolaire,
                $eleve->nom,
                $eleve->prenom,
                $eleve->annee_entree,
                $eleve->date_naissance,
                $eleve->numero_matricule,
                $eleve->educateur_responsable,
                $eleve->adresse,
                $eleve->email,
                $eleve->telephone_parent,
                $eleve->remarques,
            ];
        }
        $sheet->fromArray($rows, null, 'A4');

        // Bordures sur toutes les lignes de données
        $lastRow = 3 + count($rows);
        $sheet->getStyle("A3:L$lastRow")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ]);

        // Auto-size colonnes
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Générer le fichier en mémoire
        $filename = 'export_eleves_' . date('Y-m-d_His') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $excelOutput = ob_get_clean();

        return response($excelOutput, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
} 