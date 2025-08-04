@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg animate-fade-in">
            <div class="p-6 text-gray-900">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-brand-700 mb-2 font-sans flex items-center gap-2">
                            <span class="inline-block align-middle">📥</span> Importer des élèves
                        </h1>
                        <p class="text-gray-600 font-sans">Importez des élèves depuis un fichier CSV ou Excel</p>
                    </div>
                    <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Retour à la liste">
                        Retour à la liste
                    </a>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-blue-900">Instructions d'import</h3>
                    <ul class="text-blue-800 space-y-1">
                        <li>• Format recommandé : <strong>CSV (.csv)</strong> - plus stable</li>
                        <li>• Format Excel (.xlsx) accepté mais peut nécessiter une conversion</li>
                        <li>• Taille maximale : 5 Mo</li>
                        <li>• Colonnes optionnelles : nom, prénom, cne, numero_matricule, date_naissance, email, niveau_scolaire, etc.</li>
                        <li>• Seuls nom et prénom sont requis - tous les autres champs sont optionnels</li>
                        <li>• Téléchargez le modèle pour voir le format exact</li>
                    </ul>
                </div>

                <!-- Formulaire d'import -->
                <div class="bg-gray-50 p-6 rounded-2xl shadow-md border-l-4 border-brand-400 flex flex-col gap-6">
                    <h3 class="text-lg font-semibold mb-2 text-brand-600 flex items-center gap-2"><span>📄</span> Fichier à importer</h3>
                    <form method="POST" action="{{ route('eleves.import') }}" enctype="multipart/form-data" class="space-y-4 font-sans">
                        @csrf
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Sélectionner le fichier (CSV recommandé) *</label>
                            <input type="file" name="file" id="file" required accept=".csv,.xlsx"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-600 focus:border-transparent transition-all">
                            @error('file')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex space-x-4 mt-8">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-600 animate-fade-in">
                                Importer avec aperçu
                            </button>
                            <button type="button" id="import-direct-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-blue-600 animate-fade-in">
                                Import direct (rapide)
                            </button>
                            <a href="{{ asset('modele_import_eleves.xlsx') }}" download
                               class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-orange-400 flex items-center gap-2 shadow animate-fade-in"
                               aria-label="Télécharger le modèle Excel avec en-têtes colorés">
                                <span>📊</span>
                                Modèle Excel
                            </a>
                            <a href="{{ route('eleves.export.excel') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-blue-400 flex items-center gap-2 shadow animate-fade-in" aria-label="Exporter tous les élèves au format Excel">
                                <span>⬇️</span>
                                Exporter Excel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const importDirectBtn = document.getElementById('import-direct-btn');
    const fileInput = document.getElementById('file');

    importDirectBtn.addEventListener('click', function() {
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Veuillez sélectionner un fichier avant d\'importer.');
            return;
        }

        // Créer FormData pour l'upload
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Envoyer la requête AJAX
        fetch('{{ route("eleves.import.direct") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Import réussi ! ' + data.message);
                window.location.href = '{{ route("eleves.index") }}';
            } else {
                alert('Erreur d\'import : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de l\'import.');
        });
    });
});
</script>
@endsection
