@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8 text-gray-900">
                <!-- Header avec boutons d'action -->
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Profil Complet de l'Élève</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('eleves.export.pdf', $eleve) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            📄 Exporter PDF
                        </a>
                        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            🖨️ Imprimer
                        </button>
                        <a href="{{ route('eleves.show', $eleve) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            ← Retour
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Colonne de gauche - Photo et informations de base -->
                    <div class="lg:col-span-1">
                        <!-- Photo/Avatar -->
                        <div class="text-center mb-8">
                            <x-eleve-photo :eleve="$eleve" size="xxl" />
                            <h1 class="text-2xl font-bold text-gray-900 mt-4">{{ $eleve->nom }} {{ $eleve->prenom }}</h1>
                            <p class="text-lg text-gray-600">{{ $eleve->numero_matricule ?? 'N/A' }}</p>
                        </div>

                        <!-- Informations de base -->
                        <div class="bg-blue-50 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-bold text-blue-800 mb-4 flex items-center">
                                ℹ️ Informations de base
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Date de naissance</p>
                                    <p class="text-gray-900">{{ $eleve->date_naissance ? \Carbon\Carbon::parse($eleve->date_naissance)->format('d/m/Y') : 'Non renseignée' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Lieu de naissance</p>
                                    <p class="text-gray-900">{{ $eleve->lieu_naissance ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Sexe</p>
                                    <p class="text-gray-900">{{ $eleve->sexe == 'M' ? 'Masculin' : ($eleve->sexe == 'F' ? 'Féminin' : 'Non renseigné') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Classe</p>
                                    <p class="text-gray-900">{{ $eleve->classe->nom ?? 'Non assigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Type d'établissement</p>
                                    <p class="text-gray-900">{{ ucfirst($eleve->type_etablissement ?? 'Non défini') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Nationalité</p>
                                    <p class="text-gray-900">{{ $eleve->nationalite ?? 'Non renseignée' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Code MASSAR</p>
                                    <p class="text-gray-900">{{ $eleve->code_massar ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Date d'inscription</p>
                                    <p class="text-gray-900">{{ $eleve->date_inscription ? \Carbon\Carbon::parse($eleve->date_inscription)->format('d/m/Y') : 'Non renseignée' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne de droite - Informations détaillées -->
                    <div class="lg:col-span-2">
                        <!-- Informations de contact -->
                        <div class="bg-green-50 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
                                📞 Informations de contact
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">📧 Email</p>
                                    <p class="text-gray-900">{{ $eleve->email ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">📱 Téléphone</p>
                                    <p class="text-gray-900">{{ $eleve->telephone ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-700">🏠 Adresse</p>
                                    <p class="text-gray-900">{{ $eleve->adresse ?? 'Non renseignée' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">🆘 Contact d'urgence</p>
                                    <p class="text-gray-900">{{ $eleve->contact_urgence ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">👨‍👩‍👧‍👦 Tuteur/Parent</p>
                                    <p class="text-gray-900">{{ $eleve->nom_tuteur ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">👨‍💼 Profession du père</p>
                                    <p class="text-gray-900">{{ $eleve->profession_pere ?? 'Non renseignée' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">👩‍💼 Profession de la mère</p>
                                    <p class="text-gray-900">{{ $eleve->profession_mere ?? 'Non renseignée' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">👪 Situation familiale</p>
                                    <p class="text-gray-900">{{ $eleve->situation_familiale ?? 'Non renseignée' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">👫 Frères et sœurs</p>
                                    <p class="text-gray-900">{{ $eleve->nombre_freres_soeurs ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informations médicales -->
                        <div class="bg-red-50 p-6 rounded-lg mb-6">
                            <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center">
                                🏥 Informations médicales
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-700">🩸 Groupe sanguin</p>
                                    <p class="text-gray-900">{{ $eleve->groupe_sanguin ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">🏥 Numéro assurance</p>
                                    <p class="text-gray-900">{{ $eleve->numero_assurance ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-700">⚠️ Allergies</p>
                                    <p class="text-gray-900">{{ $eleve->allergies ?? 'Aucune' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-700">📋 Remarques médicales</p>
                                    <p class="text-gray-900">{{ $eleve->remarques_medicales ?? 'Aucune' }}</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm font-medium text-gray-700">👨‍⚕️ Médecin traitant</p>
                                    <p class="text-gray-900">{{ $eleve->medecin_traitant ?? 'Non renseigné' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">🚌 Transport scolaire</p>
                                    <p class="text-gray-900">{{ $eleve->transport_scolaire ? 'Oui' : 'Non' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">💰 Bourse d'études</p>
                                    <p class="text-gray-900">{{ $eleve->bourse_etudes ? 'Oui' : 'Non' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Parcours scolaire -->
                        @if($eleve->parcoursScolaires && $eleve->parcoursScolaires->count() > 0)
                        <div class="bg-yellow-50 p-6 rounded-lg">
                            <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center">
                                🎓 Parcours scolaire
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Année</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Établissement</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Niveau</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Résultat</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Moyenne</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($eleve->parcoursScolaires as $parcours)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $parcours->annee_scolaire }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $parcours->etablissement }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $parcours->niveau }}</td>
                                            <td class="px-4 py-2 text-sm text-gray-900">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    @if($parcours->resultat === 'Admis') bg-green-100 text-green-800
                                                    @elseif($parcours->resultat === 'Redoublant') bg-red-100 text-red-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $parcours->resultat }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-900">{{ $parcours->moyenne ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { -webkit-print-color-adjust: exact; }
}
</style>
@endsection
