<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;

class RapportStageController extends Controller
{
    /**
    * Generate a Rapport de Stage DOCX file according to the guide.
    * Query params (all optional, sensible defaults provided):
     * - type: ouvrier|etudes
     * - niveau: 1aci|2aci
     * - annee: string (e.g. 2024/2025)
     * - creneau: string (e.g. Juillet 2025)
     * - etudiant: string
     * - cycle: string
     * - option: string
     * - organisme: string
     * - ville: string
     * - periode: string
     * - encadrant_academique: string
     * - encadrant_entreprise: string
     */
    public function generate(Request $request)
    {
        $type = strtolower($request->string('type', 'etudes'));
        if (!in_array($type, ['ouvrier', 'etudes'])) {
            $type = 'etudes';
        }

        $niveau = strtolower($request->string('niveau', '1aci'));
        if (!in_array($niveau, ['1aci', '2aci'])) {
            $niveau = '1aci';
        }

        $annee = $request->string('annee', now()->subMonths(6)->format('Y') . '/' . now()->addMonths(6)->format('Y'));

        $data = [
            'type' => $type, // ouvrier | etudes
            'niveau' => strtoupper($niveau),
            'annee' => (string) $annee,
            'creneau' => $request->string('creneau', 'Été ' . now()->format('Y')),
            'etudiant' => $request->string('etudiant', 'Nom Prénom'),
            'cycle' => $request->string('cycle', 'Cycle d’Ingénieur'),
            'option' => $request->string('option', 'Option Informatique'),
            'organisme' => $request->string('organisme', 'Établissement / Entreprise'),
            'ville' => $request->string('ville', 'Ville'),
            'periode' => $request->string('periode', 'Du 01/07 au 31/07/' . now()->format('Y')),
            'encadrant_academique' => $request->string('encadrant_academique', 'Nom Encadrant Académique'),
            'encadrant_entreprise' => $request->string('encadrant_entreprise', 'Nom Encadrant Entreprise'),
            // Sections content (defaults are placeholders – user can edit in the view or pass via request if desired)
            'remerciements' => $request->string('remerciements', 'Je remercie l’équipe de l’organisme d’accueil et mon encadrant pour leur accompagnement durant ce stage.'),
            'resume' => $request->string('resume', 'Ce rapport présente le contexte, les objectifs, les missions réalisées, ainsi que les enseignements et perspectives du stage.'),
            'abreviations' => $request->input('abreviations', [ ['sigle' => 'ESI', 'definition' => 'École Supérieure d’Informatique'] ]),
            'introduction' => $request->string('introduction', "Présentation de la structure d’accueil, ses activités, les objectifs et l’intérêt du stage, la mission confiée, et la position dans l’organigramme."),
            'developpement' => $request->string('developpement', "Description détaillée des tâches effectuées, méthodes et procédures, insertion dans l’équipe, compétences mobilisées, apports personnels et professionnels, évaluation de la formation (atouts/limites), liens théorie/pratique."),
            'conclusion' => $request->string('conclusion', 'Synthèse des points essentiels, analyse critique, perspectives, et bilan sur la réussite du stage.'),
            'bibliographie' => $request->input('bibliographie', [ 'ISO 690 (1987). Documentation — Références bibliographiques. Z44-005.', 'ISO 690-2 (1998). Documents électroniques — Z44-005-2.' ]),
            'annexes' => $request->input('annexes', []),
        ];

        // Create a new Word document
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // Define title styles to enable automatic TOC
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16], ['spaceAfter' => 240]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14], ['spaceBefore' => 120, 'spaceAfter' => 120]);
        $phpWord->addTitleStyle(3, ['bold' => true, 'size' => 12], ['spaceBefore' => 120, 'spaceAfter' => 120]);

        $margins = [
            'marginLeft' => Converter::cmToTwip(2.5),
            'marginRight' => Converter::cmToTwip(2.5),
            'marginTop' => Converter::cmToTwip(2.0),
            'marginBottom' => Converter::cmToTwip(2.0),
        ];

        $paraJustify = ['alignment' => Jc::BOTH, 'lineHeight' => 1.5];
        $paraCenter = ['alignment' => Jc::CENTER];

        // Cover page section
        $cover = $phpWord->addSection($margins);
        $cover->addFooter()->addPreserveText('Page {PAGE} / {NUMPAGES}', ['size' => 10], ['alignment' => Jc::CENTER]);
        $cover->addTextBreak(6);
        $cover->addText('RAPPORT DE STAGE ' . strtoupper($data['type']), ['bold' => true, 'size' => 20], $paraCenter);
        $cover->addTextBreak(2);
        $cover->addText($data['niveau'] . ' — ' . $data['annee'], ['bold' => true, 'size' => 14], $paraCenter);
        $cover->addTextBreak(4);
        $cover->addText('Étudiant(e) : ' . $data['etudiant'], ['size' => 12], $paraCenter);
        $cover->addText('Cycle : ' . $data['cycle'], ['size' => 12], $paraCenter);
        $cover->addText('Option : ' . $data['option'], ['size' => 12], $paraCenter);
        $cover->addTextBreak(1);
        $cover->addText('Organisme : ' . $data['organisme'], ['size' => 12], $paraCenter);
        $cover->addText('Ville : ' . $data['ville'], ['size' => 12], $paraCenter);
        $cover->addText('Période : ' . $data['periode'] . ' (' . $data['creneau'] . ')', ['size' => 12], $paraCenter);
        $cover->addTextBreak(4);
        $cover->addText('Encadrant Académique : ' . $data['encadrant_academique'], ['size' => 12], $paraCenter);
        $cover->addText('Encadrant en Entreprise : ' . $data['encadrant_entreprise'], ['size' => 12], $paraCenter);
        $cover->addPageBreak();

        // TOC section
        $tocSection = $phpWord->addSection($margins);
        $tocSection->addFooter()->addPreserveText('Page {PAGE} / {NUMPAGES}', ['size' => 10], ['alignment' => Jc::CENTER]);
        $tocSection->addTitle('Table des matières', 1);
        $tocSection->addText('Conseil: Dans Word, appuyez sur F9 pour mettre à jour la table des matières.', ['italic' => true, 'size' => 10]);
        $tocSection->addTOC();
        $tocSection->addPageBreak();

        // Main content section
        $section = $phpWord->addSection($margins);
        $section->addFooter()->addPreserveText('Page {PAGE} / {NUMPAGES}', ['size' => 10], ['alignment' => Jc::CENTER]);

        // Remerciements
        $section->addTitle('Remerciements', 1);
        $section->addText($data['remerciements'], [], $paraJustify);
        $section->addPageBreak();

        // Résumé
        $section->addTitle('Résumé', 1);
        $section->addText($data['resume'], [], $paraJustify);
        $section->addPageBreak();

        // Abréviations
        $section->addTitle('Abréviations', 1);
        if (!empty($data['abreviations']) && is_array($data['abreviations'])) {
            $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 80]);
            $table->addRow();
            $table->addCell(3000)->addText('Sigle', ['bold' => true]);
            $table->addCell(9000)->addText('Définition', ['bold' => true]);
            foreach ($data['abreviations'] as $item) {
                $sigle = is_array($item) && isset($item['sigle']) ? $item['sigle'] : (string)($item['sigle'] ?? '');
                $def = is_array($item) && isset($item['definition']) ? $item['definition'] : (string)($item['definition'] ?? '');
                if ($sigle || $def) {
                    $table->addRow();
                    $table->addCell(3000)->addText($sigle);
                    $table->addCell(9000)->addText($def);
                }
            }
        } else {
            $section->addText('Aucune abréviation fournie.', ['italic' => true]);
        }
        $section->addPageBreak();

        // Introduction
        $section->addTitle('Introduction', 1);
        $section->addText($data['introduction'], [], $paraJustify);
        $section->addPageBreak();

        // Développement
        $section->addTitle('Développement', 1);
        $section->addText($data['developpement'], [], $paraJustify);
        $section->addPageBreak();

        // Conclusion
        $section->addTitle('Conclusion', 1);
        $section->addText($data['conclusion'], [], $paraJustify);
        $section->addPageBreak();

        // Bibliographie
        $section->addTitle('Bibliographie', 1);
        if (!empty($data['bibliographie']) && is_array($data['bibliographie'])) {
            foreach ($data['bibliographie'] as $entry) {
                $section->addListItem((string)$entry, 0, null, \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED);
            }
        } else {
            $section->addText('Aucune référence fournie.', ['italic' => true]);
        }

        // Annexes
        if (!empty($data['annexes']) && is_array($data['annexes'])) {
            $section->addPageBreak();
            $section->addTitle('Annexes', 1);
            $i = 1;
            foreach ($data['annexes'] as $annexe) {
                $title = is_array($annexe) && isset($annexe['titre']) ? $annexe['titre'] : (is_string($annexe) ? $annexe : 'Annexe ' . $i);
                $content = is_array($annexe) && isset($annexe['contenu']) ? $annexe['contenu'] : '';
                $section->addTitle('Annexe ' . $i . ' — ' . $title, 2);
                if ($content) {
                    $section->addText((string)$content, [], $paraJustify);
                }
                $i++;
            }
        }

        // Save to a temporary file and return as download
        $filename = 'rapport_de_stage_' . preg_replace('/\s+/', '_', (string)$data['etudiant']) . '.docx';
        $tmpPath = tempnam(sys_get_temp_dir(), 'rapport_');
        // Ensure .docx extension for better client handling
        $docxPath = $tmpPath . '.docx';
        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($docxPath);

        return response()->download($docxPath, $filename)->deleteFileAfterSend(true);
    }
}
