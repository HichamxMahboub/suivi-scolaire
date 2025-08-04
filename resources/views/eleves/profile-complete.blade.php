@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Header avec actions -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Profil Complet - {{ $eleve->prenom }} {{ $eleve->nom }}</h1>
                        <p class="text-gray-600">Toutes les informations détaillées de l'élève</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('eleves.export.pdf', $eleve) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                            📄 Exporter PDF
                        </a>
                        <button onclick="window.print()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                            🖨️ Imprimer
                        </button>
                        <a href="{{ route('eleves.show', $eleve) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-200">
                            ← Retour
                        </a>
                    </div>
                </div>

                <!-- Informations principales -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-8">
                    <!-- Photo -->
                    <div class="text-center">
                        <div class="w-40 h-40 mx-auto mb-4">
                            @if($eleve->photo)
                                <img src="{{ asset('storage/' . $eleve->photo) }}" alt="Photo de {{ $eleve->nom }}" class="w-40 h-40 rounded-full object-cover border-4 border-blue-300 shadow-lg">
                            @else
                                <div class="w-40 h-40 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center border-4 border-blue-300 shadow-lg">
                                    <span class="text-5xl font-bold text-white">
                                        {{ strtoupper(substr($eleve->nom, 0, 1) . substr($eleve->prenom, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $eleve->prenom }} {{ $eleve->nom }}</h2>
                        <p class="text-gray-600">{{ $eleve->numero_matricule ?? 'N/A' }}</p>
                    </div>

                    <!-- Informations de base -->
                    <div class="lg:col-span-3">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl shadow-md">
                            <h3 class="text-xl font-bold text-blue-800 mb-4 flex items-center">
                                ℹ️ Informations de base
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                    <p class="text-sm font-medium text-gray-600">Date de naissance</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                    <p class="text-sm font-medium text-gray-600">Lieu de naissance</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $eleve->lieu_naissance ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                    <p class="text-sm font-medium text-gray-600">Sexe</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $eleve->sexe == 'M' ? 'Masculin' : ($eleve->sexe == 'F' ? 'Féminin' : 'Non renseigné') }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                    <p class="text-sm font-medium text-gray-600">Classe</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $eleve->classe->nom ?? 'Non assigné' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                    <p class="text-sm font-medium text-gray-600">Type d'établissement</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ ucfirst($eleve->type_etablissement ?? 'Non défini') }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-lg shadow-sm">
                                    <p class="text-sm font-medium text-gray-600">Code MASSAR</p>
                                    <p class="text-lg font-semibold text-gray-900">{{ $eleve->code_massar ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations détaillées en sections -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Informations de contact -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-bold text-green-800 mb-4 flex items-center">
                            📞 Informations de contact
                        </h3>
                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">📧 Email</p>
                                <p class="text-lg text-gray-900">{{ $eleve->email ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">📱 Téléphone</p>
                                <p class="text-lg text-gray-900">{{ $eleve->telephone ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">🏠 Adresse</p>
                                <p class="text-lg text-gray-900">{{ $eleve->adresse ?? 'Non renseignée' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">🆘 Contact d'urgence</p>
                                <p class="text-lg text-gray-900">{{ $eleve->contact_urgence ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">👨‍👩‍👧‍👦 Nom tuteur/parent</p>
                                <p class="text-lg text-gray-900">{{ $eleve->nom_tuteur ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informations médicales -->
                    <div class="bg-gradient-to-r from-red-50 to-pink-50 p-6 rounded-xl shadow-md">
                        <h3 class="text-xl font-bold text-red-800 mb-4 flex items-center">
                            🏥 Informations médicales
                        </h3>
                        <div class="space-y-3">
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">🩸 Groupe sanguin</p>
                                <p class="text-lg text-gray-900">{{ $eleve->groupe_sanguin ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">⚠️ Allergies</p>
                                <p class="text-lg text-gray-900">{{ $eleve->allergies ?? 'Aucune' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">📋 Remarques médicales</p>
                                <p class="text-lg text-gray-900">{{ $eleve->remarques_medicales ?? 'Aucune' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">👨‍⚕️ Médecin traitant</p>
                                <p class="text-lg text-gray-900">{{ $eleve->medecin_traitant ?? 'Non renseigné' }}</p>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm">
                                <p class="text-sm font-medium text-gray-600">🏥 Numéro assurance</p>
                                <p class="text-lg text-gray-900">{{ $eleve->numero_assurance ?? 'Non renseigné' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations familiales et sociales -->
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-6 rounded-xl shadow-md mb-8">
                    <h3 class="text-xl font-bold text-purple-800 mb-6 flex items-center">
                        👨‍👩‍👧‍👦 Informations familiales et sociales
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-gray-600">🌍 Nationalité</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $eleve->nationalite ?? 'Non renseignée' }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-gray-600">👪 Situation familiale</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $eleve->situation_familiale ?? 'Non renseignée' }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-gray-600">👨‍💼 Profession du père</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $eleve->profession_pere ?? 'Non renseignée' }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-gray-600">👩‍💼 Profession de la mère</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $eleve->profession_mere ?? 'Non renseignée' }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-gray-600">👫 Frères et sœurs</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $eleve->nombre_freres_soeurs ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-gray-600">🚌 Transport scolaire</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $eleve->transport_scolaire ? 'Oui' : 'Non' }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-gray-600">💰 Bourse d'études</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $eleve->bourse_etudes ? 'Oui' : 'Non' }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm">
                            <p class="text-sm font-medium text-gray-600">📅 Date d'inscription</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $eleve->date_inscription ? \Carbon\Carbon::parse($eleve->date_inscription)->format('d/m/Y') : 'Non renseignée' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Parcours scolaire -->
                @if($eleve->parcoursScolaires && $eleve->parcoursScolaires->count() > 0)
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-xl shadow-md mb-8">
                    <h3 class="text-xl font-bold text-orange-800 mb-6 flex items-center">
                        🎓 Parcours scolaire
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white rounded-lg shadow-sm">
                            <thead class="bg-orange-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-orange-800 uppercase tracking-wider">Année</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-orange-800 uppercase tracking-wider">Établissement</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-orange-800 uppercase tracking-wider">Niveau</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-orange-800 uppercase tracking-wider">Résultat</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-orange-800 uppercase tracking-wider">Moyenne</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($eleve->parcoursScolaires as $parcours)
                                <tr class="hover:bg-orange-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $parcours->annee_scolaire }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $parcours->etablissement }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $parcours->niveau }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $parcours->resultat == 'Admis' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $parcours->resultat }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $parcours->moyenne ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- Footer avec informations système -->
                <div class="border-t pt-6 mt-8">
                    <div class="flex justify-between items-center text-sm text-gray-500">
                        <p>Profil généré le {{ date('d/m/Y à H:i') }}</p>
                        <p>Système de Suivi Scolaire - École</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        
        body {
            background: white !important;
        }
        
        .shadow-md, .shadow-lg, .shadow-sm {
            box-shadow: none !important;
        }
    }
</style>
@endsection
