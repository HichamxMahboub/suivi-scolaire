<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rapport de stage</title>
    <style>
        @page { margin: 2.5cm 2.5cm 2cm 2.5cm; }
        body { font-family: DejaVu Sans, sans-serif; color: #000; }
        .page-break { page-break-after: always; }
        h1, h2, h3 { margin: 0 0 8px; }
        h1 { font-size: 20pt; }
        h2 { font-size: 16pt; margin-top: 18px; }
        h3 { font-size: 13pt; margin-top: 14px; }
        p, li { font-size: 12pt; line-height: 1.4; text-align: justify; }
        .meta { font-size: 12pt; }
        .center { text-align: center; }
        .small { font-size: 11pt; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; vertical-align: top; padding: 4px 0; }
        .toc li { list-style: none; margin: 4px 0; }
        footer { position: fixed; bottom: 0.6cm; left: 0; right: 0; text-align: center; font-size: 10pt; color: #333; }
    </style>
</head>
<body>
    <!-- Page de garde -->
    <div class="center">
        <h1>Rapport de stage ({{ strtoupper($type) }})</h1>
        <p class="meta">{{ $niveau }} — Année universitaire {{ $annee }}</p>
        <p class="meta">Créneau du stage: {{ $creneau }}</p>
        <br>
        <table>
            <tr><td><strong>Étudiant:</strong></td><td>{{ $etudiant }}</td></tr>
            <tr><td><strong>Cycle et option:</strong></td><td>{{ $cycle }} — {{ $option }}</td></tr>
            <tr><td><strong>Organisme d’accueil:</strong></td><td>{{ $organisme }} ({{ $ville }})</td></tr>
            <tr><td><strong>Période:</strong></td><td>{{ $periode }}</td></tr>
            <tr><td><strong>Encadrant académique:</strong></td><td>{{ $encadrant_academique }}</td></tr>
            <tr><td><strong>Encadrant en entreprise:</strong></td><td>{{ $encadrant_entreprise }}</td></tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Remerciements -->
    <h2>Remerciements</h2>
    <p>{{ $remerciements }}</p>

    <div class="page-break"></div>

    <!-- Résumé -->
    <h2>Résumé</h2>
    <p>{{ $resume }}</p>

    <div class="page-break"></div>

    <!-- Table de matières (statique simple) -->
    <h2>Table de matières</h2>
    <ul class="toc small">
        <li>Remerciements</li>
        <li>Résumé</li>
        <li>Liste des abréviations</li>
        <li>Liste des tableaux</li>
        <li>Liste des figures</li>
        <li>Introduction</li>
        <li>Développement</li>
        <li>Conclusion</li>
        <li>Bibliographie</li>
        <li>Annexes</li>
    </ul>

    <div class="page-break"></div>

    <!-- Abréviations -->
    <h2>Liste des abréviations</h2>
    @if(!empty($abreviations))
        <table>
            @foreach($abreviations as $abv)
                <tr>
                    <td style="width:25%"><strong>{{ $abv['sigle'] ?? '' }}</strong></td>
                    <td>{{ $abv['definition'] ?? '' }}</td>
                </tr>
            @endforeach
        </table>
    @else
        <p class="small">Aucune abréviation déclarée.</p>
    @endif

    <div class="page-break"></div>

    <!-- Introduction -->
    <h2>Introduction</h2>
    <p>{{ $introduction }}</p>

    <div class="page-break"></div>

    <!-- Développement -->
    <h2>Développement</h2>
    <p>{!! nl2br(e($developpement)) !!}</p>

    <div class="page-break"></div>

    <!-- Conclusion -->
    <h2>Conclusion</h2>
    <p>{{ $conclusion }}</p>

    <div class="page-break"></div>

    <!-- Bibliographie -->
    <h2>Bibliographie</h2>
    @if(!empty($bibliographie))
        <ul>
            @foreach($bibliographie as $ref)
                <li class="small">{{ is_string($ref) ? $ref : json_encode($ref, JSON_UNESCAPED_UNICODE) }}</li>
            @endforeach
        </ul>
    @else
        <p class="small">Aucune référence.</p>
    @endif

    <div class="page-break"></div>

    <!-- Annexes -->
    <h2>Annexes</h2>
    @if(!empty($annexes))
        @foreach($annexes as $annexe)
            <h3>{{ $annexe['titre'] ?? 'Annexe' }}</h3>
            <p class="small">{!! nl2br(e($annexe['contenu'] ?? '')) !!}</p>
        @endforeach
    @else
        <p class="small">Aucune annexe fournie.</p>
    @endif

    <footer>
        Rapport de stage — {{ $etudiant }} — {{ $annee }} — Page <span class="pagenum"></span>
    </footer>
    <script type="text/php">
        if (isset($pdf)) {
            $x = 520; $y = 810; $text = $PAGE_NUM . '/' . $PAGE_COUNT; $size = 10; $color = [0,0,0];
            $font = $fontMetrics->get_font('DejaVu Sans', 'normal');
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
</body>
</html>
