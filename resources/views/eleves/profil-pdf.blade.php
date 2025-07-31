<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profil de {{ $eleve->nom }} {{ $eleve->prenom }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            color: #333; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #333; 
            padding-bottom: 20px; 
        }
        .photo { 
            width: 120px; 
            height: 120px; 
            border-radius: 50%; 
            object-fit: cover; 
            margin: 0 auto 15px; 
            display: block; 
            border: 3px solid #ccc; 
        }
        .avatar { 
            width: 120px; 
            height: 120px; 
            border-radius: 50%; 
            background-color: #e5e5e5; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            margin: 0 auto 15px; 
            font-size: 36px; 
            font-weight: bold; 
            color: #666; 
            border: 3px solid #ccc; 
        }
        .section { 
            margin-bottom: 25px; 
            page-break-inside: avoid; 
        }
        .section-title { 
            background-color: #f0f0f0; 
            padding: 10px; 
            margin-bottom: 15px; 
            font-weight: bold; 
            font-size: 16px; 
            border-left: 4px solid #007cba; 
        }
        .row { 
            display: flex; 
            margin-bottom: 8px; 
        }
        .label { 
            font-weight: bold; 
            width: 180px; 
            flex-shrink: 0; 
        }
        .value { 
            flex: 1; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f0f0f0; 
            font-weight: bold; 
        }
        .grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 20px; 
        }
        @media print {
            body { margin: 0; }
            .section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        @if($eleve->photo)
            <img src="{{ public_path('storage/' . $eleve->photo) }}" alt="Photo de {{ $eleve->nom }}" class="photo">
        @else
            <div class="avatar">
                {{ strtoupper(substr($eleve->nom, 0, 1) . substr($eleve->prenom, 0, 1)) }}
            </div>
        @endif
        <h1>{{ $eleve->nom }} {{ $eleve->prenom }}</h1>
        <p>Matricule: {{ $eleve->numero_matricule ?? 'N/A' }}</p>
        <p>Généré le {{ now()->format('d/m/Y à H:i') }}</p>
    </div>

    <div class="grid">
        <div>
            <!-- Informations de base -->
            <div class="section">
                <div class="section-title">📋 Informations de base</div>
                <div class="row">
                    <div class="label">Date de naissance:</div>
                    <div class="value">{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</div>
                </div>
                <div class="row">
                    <div class="label">Lieu de naissance:</div>
                    <div class="value">{{ $eleve->lieu_naissance ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Sexe:</div>
                    <div class="value">{{ $eleve->sexe == 'M' ? 'Masculin' : ($eleve->sexe == 'F' ? 'Féminin' : 'Non renseigné') }}</div>
                </div>
                <div class="row">
                    <div class="label">Nationalité:</div>
                    <div class="value">{{ $eleve->nationalite ?? 'Non renseignée' }}</div>
                </div>
                <div class="row">
                    <div class="label">Classe:</div>
                    <div class="value">{{ $eleve->classe->nom ?? 'Non assigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Type d'établissement:</div>
                    <div class="value">{{ ucfirst($eleve->type_etablissement ?? 'Non défini') }}</div>
                </div>
                <div class="row">
                    <div class="label">Code MASSAR:</div>
                    <div class="value">{{ $eleve->code_massar ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Date d'inscription:</div>
                    <div class="value">{{ $eleve->date_inscription ? \Carbon\Carbon::parse($eleve->date_inscription)->format('d/m/Y') : 'Non renseignée' }}</div>
                </div>
            </div>

            <!-- Informations médicales -->
            <div class="section">
                <div class="section-title">🏥 Informations médicales</div>
                <div class="row">
                    <div class="label">Groupe sanguin:</div>
                    <div class="value">{{ $eleve->groupe_sanguin ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Numéro assurance:</div>
                    <div class="value">{{ $eleve->numero_assurance ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Allergies:</div>
                    <div class="value">{{ $eleve->allergies ?? 'Aucune' }}</div>
                </div>
                <div class="row">
                    <div class="label">Remarques médicales:</div>
                    <div class="value">{{ $eleve->remarques_medicales ?? 'Aucune' }}</div>
                </div>
                <div class="row">
                    <div class="label">Médecin traitant:</div>
                    <div class="value">{{ $eleve->medecin_traitant ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Transport scolaire:</div>
                    <div class="value">{{ $eleve->transport_scolaire ? 'Oui' : 'Non' }}</div>
                </div>
                <div class="row">
                    <div class="label">Bourse d'études:</div>
                    <div class="value">{{ $eleve->bourse_etudes ? 'Oui' : 'Non' }}</div>
                </div>
            </div>
        </div>

        <div>
            <!-- Informations de contact -->
            <div class="section">
                <div class="section-title">📞 Informations de contact</div>
                <div class="row">
                    <div class="label">Email:</div>
                    <div class="value">{{ $eleve->email ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Téléphone:</div>
                    <div class="value">{{ $eleve->telephone ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Adresse:</div>
                    <div class="value">{{ $eleve->adresse ?? 'Non renseignée' }}</div>
                </div>
                <div class="row">
                    <div class="label">Contact d'urgence:</div>
                    <div class="value">{{ $eleve->contact_urgence ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Tuteur/Parent:</div>
                    <div class="value">{{ $eleve->nom_tuteur ?? 'Non renseigné' }}</div>
                </div>
                <div class="row">
                    <div class="label">Profession du père:</div>
                    <div class="value">{{ $eleve->profession_pere ?? 'Non renseignée' }}</div>
                </div>
                <div class="row">
                    <div class="label">Profession de la mère:</div>
                    <div class="value">{{ $eleve->profession_mere ?? 'Non renseignée' }}</div>
                </div>
                <div class="row">
                    <div class="label">Situation familiale:</div>
                    <div class="value">{{ $eleve->situation_familiale ?? 'Non renseignée' }}</div>
                </div>
                <div class="row">
                    <div class="label">Frères et sœurs:</div>
                    <div class="value">{{ $eleve->nombre_freres_soeurs ?? 'Non renseigné' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Parcours scolaire -->
    @if($eleve->parcoursScolaires && $eleve->parcoursScolaires->count() > 0)
    <div class="section">
        <div class="section-title">🎓 Parcours scolaire</div>
        <table>
            <thead>
                <tr>
                    <th>Année</th>
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
                    <td>{{ $parcours->resultat }}</td>
                    <td>{{ $parcours->moyenne ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</body>
</html>
