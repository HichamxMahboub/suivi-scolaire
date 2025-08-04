@extends('layouts.ap                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-blue-900">Instructions d'import</h3>
                    <ul class="text-blue-800 space-y-1">
                        <li>• Format recommandé : <strong>CSV (.csv)</strong> - plus stable</li>
                        <li>• Format Excel (.xlsx) accepté mais peut nécessiter une conversion</li>
                        <li>• Taille maximale : 5 Mo</li>
                        <li>• Colonnes optionnelles : nom, prénom, cne, numero_matricule, date_naissance, email, niveau_scolaire, etc.</li>
                        <li>• Seuls nom et prénom sont requis - tous les autres champs sont optionnels</li>
                        <li>• Téléchargez le modèle pour voir le format exact</li>
                    </ul>
                </div>tion('content')
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
                        <p class="text-gray-600 font-sans">Importez des élèves depuis un fichier Excel ou CSV</p>
                    </div>
                    <a href="{{ route('eleves.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-brand-400" aria-label="Retour à la liste">
                        Retour à la liste
                    </a>
                </div>
                <!-- Instructions -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-lg font-semibold mb-2 text-blue-900">Instructions d'import</h3>
                    <ul class="text-blue-800 space-y-1">
                        <li>• Format accepté : <strong>Excel (.xlsx)</strong> uniquement</li>
                        <li>• Taille maximale : 5 Mo</li>
                        <li>• Colonnes requises : N°, Niveau scolaire, Nom et prénom, Année d’entrée, Date de naissance, n° d’immatriculation, Éducateur responsable, etc.</li>
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
                            </a>
                            <a href="{{ route('eleves.export.excel') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-all focus:outline-none focus:ring-2 focus:ring-blue-400 flex items-center gap-2 shadow animate-fade-in" aria-label="Exporter tous les élèves au format Excel">
                                <span>⬇️</span>
                                Exporter Excel
                            </a>
                        </div>
                    </form>
                </div>
                <!-- Résultats d'import précédent -->
                @if(session('import_results'))
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900">Résultats de l'import</h3>
                    <div class="space-y-2">
                        @if(session('import_results.success') > 0)
                        <div class="flex items-center text-green-700">
                            <span class="mr-2">✅</span>
                            <span>{{ session('import_results.success') }} élèves importés avec succès</span>
                        </div>
                        @endif
                        @if(session('import_results.errors') > 0)
                        <div class="flex items-center text-red-700">
                            <span class="mr-2">❌</span>
                            <span>{{ session('import_results.errors') }} erreurs rencontrées</span>
                        </div>
                        @endif
                        @if(session('import_results.skipped') > 0)
                        <div class="flex items-center text-yellow-700">
                            <span class="mr-2">⚠️</span>
                            <span>{{ session('import_results.skipped') }} lignes ignorées</span>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                <!-- Erreurs détaillées -->
                @if(session('import_errors') && count(session('import_errors')) > 0)
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4 text-red-900">Erreurs détaillées</h3>
                    <div class="space-y-2 max-h-60 overflow-y-auto">
                        @foreach(session('import_errors') as $error)
                        <div class="text-red-800 text-sm">
                            <strong>Ligne {{ $error['line'] }}:</strong> {{ $error['message'] }}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de statut d'import -->
<div id="import-status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div id="import-status-icon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            </div>
            <h3 id="import-status-title" class="text-lg leading-6 font-medium text-gray-900 mt-2">Import en cours...</h3>
            <div class="mt-2 px-7 py-3">
                <p id="import-status-message" class="text-sm text-gray-500">
                    Veuillez patienter pendant l'import des élèves...
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="close-modal-btn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 hidden">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const importDirectBtn = document.getElementById('import-direct-btn');
    const fileInput = document.getElementById('file');
    const modal = document.getElementById('import-status-modal');
    const modalIcon = document.getElementById('import-status-icon');
    const modalTitle = document.getElementById('import-status-title');
    const modalMessage = document.getElementById('import-status-message');
    const closeBtn = document.getElementById('close-modal-btn');

    function showModal() {
        modal.classList.remove('hidden');
    }

    function hideModal() {
        modal.classList.add('hidden');
    }

    function updateModal(title, message, isSuccess = null, isError = null) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        
        if (isSuccess) {
            modalIcon.innerHTML = '<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100"><svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>';
            modalIcon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100';
            closeBtn.classList.remove('hidden');
        } else if (isError) {
            modalIcon.innerHTML = '<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100"><svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></div>';
            modalIcon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100';
            closeBtn.classList.remove('hidden');
        } else {
            modalIcon.innerHTML = '<div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>';
            modalIcon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100';
            closeBtn.classList.add('hidden');
        }
    }

    closeBtn.addEventListener('click', function() {
        hideModal();
        // Rediriger vers la liste des élèves après un import réussi
        if (modalTitle.textContent.includes('succès')) {
            window.location.href = '{{ route("eleves.index") }}';
        }
    });

    importDirectBtn.addEventListener('click', function() {
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Veuillez sélectionner un fichier avant d\'importer.');
            return;
        }

        // Vérifier l'extension du fichier
        const allowedExtensions = ['.csv', '.xlsx'];
        const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));
        
        if (!allowedExtensions.includes(fileExtension)) {
            alert('Format de fichier non supporté. Utilisez CSV ou Excel (.xlsx).');
            return;
        }

        // Créer FormData pour l'upload
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        // Afficher le modal de chargement
        showModal();
        updateModal('Import en cours...', 'Veuillez patienter pendant l\'import des élèves...');

        // Envoyer la requête AJAX
        fetch('{{ route("eleves.import.direct") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateModal('Import réussi !', data.message, true, false);
            } else {
                updateModal('Erreur d\'import', data.message, false, true);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            updateModal('Erreur réseau', 'Une erreur est survenue lors de l\'import. Veuillez réessayer.', false, true);
        });
    });
});
</script>
@endsection