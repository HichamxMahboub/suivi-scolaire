<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil de {{ $eleve->prenom }} {{ $eleve->nom }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #1e40af;
            margin: 0;
            font-size: 28px;
        }
        
        .header p {
            color: #6b7280;
            margin: 5px 0;
        }
        
        .photo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #3b82f6;
            object-fit: cover;
        }
        
        .photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #3b82f6;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: bold;
        }
        
        .section {
            margin-bottom: 30px;
            break-inside: avoid;
        }
        
        .section-title {
            background: #f3f4f6;
            padding: 10px 15px;
            border-left: 4px solid #3b82f6;
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .info-item {
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 5px;
            background: #fafafa;
        }
        
        .info-label {
            font-weight: bold;
            color: #4b5563;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #111827;
            font-size: 14px;
        }
        
        .parcours-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .parcours-table th,
        .parcours-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        
        .parcours-table th {
            background: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        
        .success {
            background: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .failure {
            background: #fecaca;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        
        @page {
            margin: 2cm;
        }
    </style>
</head>
<body>
    <!-- En-tête -->
    <div class="header">
        <h1>PROFIL ÉLÈVE</h1>
        <p>{{ $eleve->prenom }} {{ $eleve->nom }}</p>
        <p>Numéro matricule: {{ $eleve->numero_matricule ?? 'N/A' }}</p>
    </div>

    <!-- Photo -->
    <div class="photo-section">
        @if($eleve->photo)
            <img src="{{ public_path('storage/' . $eleve->photo) }}" alt="Photo" class="photo">
        @else
            <div class="photo-placeholder">
                {{ strtoupper(substr($eleve->nom, 0, 1) . substr($eleve->prenom, 0, 1)) }}
            </div>
        @endif
    </div>

    <!-- Informations de base -->
    <div class="section">
        <div class="section-title">ℹ️ INFORMATIONS DE BASE</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Date de naissance</div>
                <div class="info-value">{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Lieu de naissance</div>
                <div class="info-value">{{ $eleve->lieu_naissance ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Sexe</div>
                <div class="info-value">{{ $eleve->sexe == 'M' ? 'Masculin' : ($eleve->sexe == 'F' ? 'Féminin' : 'Non renseigné') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Nationalité</div>
                <div class="info-value">{{ $eleve->nationalite ?? 'Non renseignée' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Classe</div>
                <div class="info-value">{{ $eleve->classe->nom ?? 'Non assigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Type d'établissement</div>
                <div class="info-value">{{ ucfirst($eleve->type_etablissement ?? 'Non défini') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Code MASSAR</div>
                <div class="info-value">{{ $eleve->code_massar ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Date d'inscription</div>
                <div class="info-value">{{ $eleve->date_inscription ? \Carbon\Carbon::parse($eleve->date_inscription)->format('d/m/Y') : 'Non renseignée' }}</div>
            </div>
        </div>
    </div>

    <!-- Informations de contact -->
    <div class="section">
        <div class="section-title">📞 INFORMATIONS DE CONTACT</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $eleve->email ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Téléphone</div>
                <div class="info-value">{{ $eleve->telephone ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Contact d'urgence</div>
                <div class="info-value">{{ $eleve->contact_urgence ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Nom tuteur/parent</div>
                <div class="info-value">{{ $eleve->nom_tuteur ?? 'Non renseigné' }}</div>
            </div>
        </div>
        <div class="info-item" style="grid-column: span 2; margin-top: 10px;">
            <div class="info-label">Adresse complète</div>
            <div class="info-value">{{ $eleve->adresse ?? 'Non renseignée' }}</div>
        </div>
    </div>

    <!-- Informations familiales -->
    <div class="section">
        <div class="section-title">👨‍👩‍👧‍👦 INFORMATIONS FAMILIALES</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Situation familiale</div>
                <div class="info-value">{{ $eleve->situation_familiale ?? 'Non renseignée' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Nombre de frères et sœurs</div>
                <div class="info-value">{{ $eleve->nombre_freres_soeurs ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Profession du père</div>
                <div class="info-value">{{ $eleve->profession_pere ?? 'Non renseignée' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Profession de la mère</div>
                <div class="info-value">{{ $eleve->profession_mere ?? 'Non renseignée' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Transport scolaire</div>
                <div class="info-value">{{ $eleve->transport_scolaire ? 'Oui' : 'Non' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Bourse d'études</div>
                <div class="info-value">{{ $eleve->bourse_etudes ? 'Oui' : 'Non' }}</div>
            </div>
        </div>
    </div>

    <!-- Informations médicales -->
    <div class="section">
        <div class="section-title">🏥 INFORMATIONS MÉDICALES</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Groupe sanguin</div>
                <div class="info-value">{{ $eleve->groupe_sanguin ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Numéro assurance</div>
                <div class="info-value">{{ $eleve->numero_assurance ?? 'Non renseigné' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Médecin traitant</div>
                <div class="info-value">{{ $eleve->medecin_traitant ?? 'Non renseigné' }}</div>
            </div>
        </div>
        @if($eleve->allergies)
        <div class="info-item" style="margin-top: 10px;">
            <div class="info-label">Allergies connues</div>
            <div class="info-value">{{ $eleve->allergies }}</div>
        </div>
        @endif
        @if($eleve->remarques_medicales)
        <div class="info-item" style="margin-top: 10px;">
            <div class="info-label">Remarques médicales</div>
            <div class="info-value">{{ $eleve->remarques_medicales }}</div>
        </div>
        @endif
    </div>

    <!-- Parcours scolaire -->
    @if($eleve->parcoursScolaires && $eleve->parcoursScolaires->count() > 0)
    <div class="section">
        <div class="section-title">🎓 PARCOURS SCOLAIRE</div>
        <table class="parcours-table">
            <thead>
                <tr>
                    <th>Année scolaire</th>
                    <th>Établissement</th>
                    <th>Niveau</th>
                    <th>Résultat</th>
                    <th>Moyenne</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eleve->parcoursScolaires as $parcours)
                <tr>
                    <td>{{ $parcours->annee_scolaire }}</td>
                    <td>{{ $parcours->etablissement }}</td>
                    <td>{{ $parcours->niveau }}</td>
                    <td>
                        <span class="{{ $parcours->resultat == 'Admis' ? 'success' : 'failure' }}">
                            {{ $parcours->resultat }}
                        </span>
                    </td>
                    <td>{{ $parcours->moyenne ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Document généré le {{ date('d/m/Y à H:i') }}</p>
        <p>Système de Suivi Scolaire - École</p>
    </div>
</body>
</html>
