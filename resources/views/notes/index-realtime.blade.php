<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Notes - Temps Réel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <!-- Indicateur de statut de connexion -->
        <div id="status-indicator" class="fixed top-4 right-4 z-50">
            <div class="bg-green-500 text-white px-3 py-1 rounded-full text-sm flex items-center">
                <div id="status-dot" class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></div>
                <span id="status-text">En ligne</span>
            </div>
        </div>

        <!-- Notification de mise à jour -->
        <div id="update-notification" class="fixed top-16 right-4 z-50 hidden">
            <div class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg">
                <div class="flex items-center">
                    <span>Nouvelles données disponibles</span>
                    <button onclick="forceRefresh()" class="ml-2 bg-white text-blue-500 px-2 py-1 rounded text-xs">
                        Actualiser
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                Gestion des Notes 
                <span class="text-sm text-gray-500">(Temps Réel)</span>
            </h1>
            <div class="flex space-x-2">
                <button onclick="toggleAutoRefresh()" id="auto-refresh-btn" 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    ⏸️ Pause Auto
                </button>
                <a href="{{ route('notes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Ajouter une Note
                </a>
            </div>
        </div>

        <!-- Navigation -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">← Retour au tableau de bord</a>
        </div>

        <!-- Paramètres temps réel -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700">Mise à jour :</span>
                    <select id="refresh-interval" onchange="updateRefreshInterval()" 
                            class="text-sm border border-gray-300 rounded px-2 py-1">
                        <option value="5000">5 secondes</option>
                        <option value="10000" selected>10 secondes</option>
                        <option value="30000">30 secondes</option>
                        <option value="60000">1 minute</option>
                    </select>
                </div>
                <div class="text-sm text-gray-500">
                    Dernière mise à jour : <span id="last-update">{{ now()->format('H:i:s') }}</span>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Filtres</h3>
            <form id="filter-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="Nom élève, matière..." 
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="matiere" class="block text-sm font-medium text-gray-700">Matière</label>
                    <select name="matiere" id="matiere" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Toutes les matières</option>
                        @foreach($matieres as $mat)
                            <option value="{{ $mat }}" {{ request('matiere') == $mat ? 'selected' : '' }}>
                                {{ $mat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="semestre" class="block text-sm font-medium text-gray-700">Semestre</label>
                    <select name="semestre" id="semestre" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Tous les semestres</option>
                        @foreach($semestres as $sem)
                            <option value="{{ $sem }}" {{ request('semestre') == $sem ? 'selected' : '' }}>
                                {{ $sem }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="button" onclick="applyFilters()" class="w-full bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('notes.statistiques') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    📊 Statistiques
                </a>
                <a href="{{ route('eleves.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    📋 Voir Bulletins
                </a>
            </div>
        </div>

        <!-- Messages de succès -->
        <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline" id="success-text"></span>
        </div>

        <!-- Statistiques rapides -->
        <div id="stats-container" class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div id="total-notes" class="text-2xl font-bold text-blue-600">{{ $notes->count() }}</div>
                    <div class="text-gray-600">Total Notes</div>
                </div>
                <div>
                    <div id="admis-count" class="text-2xl font-bold text-green-600">{{ $notes->where('note_vingt', '>=', 10)->count() }}</div>
                    <div class="text-gray-600">Admis</div>
                </div>
                <div>
                    <div id="insuffisant-count" class="text-2xl font-bold text-red-600">{{ $notes->where('note_vingt', '<', 10)->count() }}</div>
                    <div class="text-gray-600">Insuffisant</div>
                </div>
                <div>
                    <div id="moyenne-generale" class="text-2xl font-bold text-purple-600">{{ number_format($notes->avg('note_vingt'), 2) }}</div>
                    <div class="text-gray-600">Moyenne</div>
                </div>
            </div>
        </div>

        <!-- Liste des notes -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Liste des Notes</h3>
                    <div class="flex items-center space-x-2">
                        <div id="loading-indicator" class="hidden">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600"></div>
                        </div>
                        <span class="text-sm text-gray-500">
                            <span id="notes-count">{{ $notes->count() }}</span> notes affichées
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="notes-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Élève
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Matière
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Évaluation
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Note
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Semestre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="notes-tbody" class="bg-white divide-y divide-gray-200">
                            @forelse($notes as $note)
                                <tr class="hover:bg-gray-50" data-note-id="{{ $note->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $note->eleve->nom }} {{ $note->eleve->prenom }}
                                                </div>
                                                @if($note->eleve->classe)
                                                    <div class="text-sm text-gray-500">
                                                        {{ $note->eleve->classe->nom }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $note->matiere }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $note->type_evaluation }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium {{ $note->couleur }}">
                                            {{ $note->note }}/{{ $note->note_sur }}
                                            <span class="text-gray-500">({{ $note->note_vingt }}/20)</span>
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $note->mention }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $note->date_evaluation->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $note->semestre === 'S1' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $note->semestre }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('notes.show', $note) }}" 
                                               class="text-indigo-600 hover:text-indigo-900" title="Voir">
                                                👁️
                                            </a>
                                            <a href="{{ route('notes.edit', $note) }}" 
                                               class="text-yellow-600 hover:text-yellow-900" title="Modifier">
                                                ✏️
                                            </a>
                                            <button onclick="deleteNote({{ $note->id }})" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Supprimer">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-notes-row">
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Aucune note trouvée.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript pour la mise à jour en temps réel -->
    <script>
        let autoRefreshEnabled = true;
        let refreshInterval = 10000; // 10 secondes par défaut
        let intervalId = null;
        let lastUpdateHash = '';

        // Configuration CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            startAutoRefresh();
            setupEventListeners();
            updateLastUpdateTime();
        });

        function setupEventListeners() {
            // Écouter les changements de filtres
            document.getElementById('search').addEventListener('input', debounce(applyFilters, 500));
            document.getElementById('matiere').addEventListener('change', applyFilters);
            document.getElementById('semestre').addEventListener('change', applyFilters);
        }

        function startAutoRefresh() {
            if (intervalId) clearInterval(intervalId);
            
            if (autoRefreshEnabled) {
                intervalId = setInterval(function() {
                    refreshData();
                }, refreshInterval);
            }
        }

        function toggleAutoRefresh() {
            autoRefreshEnabled = !autoRefreshEnabled;
            const btn = document.getElementById('auto-refresh-btn');
            
            if (autoRefreshEnabled) {
                btn.innerHTML = '⏸️ Pause Auto';
                btn.className = 'bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded';
                startAutoRefresh();
                updateStatus('En ligne', 'green');
            } else {
                btn.innerHTML = '▶️ Reprendre Auto';
                btn.className = 'bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded';
                clearInterval(intervalId);
                updateStatus('En pause', 'gray');
            }
        }

        function updateRefreshInterval() {
            const select = document.getElementById('refresh-interval');
            refreshInterval = parseInt(select.value);
            
            if (autoRefreshEnabled) {
                startAutoRefresh();
            }
        }

        function refreshData() {
            showLoading(true);
            
            const formData = new FormData(document.getElementById('filter-form'));
            const params = new URLSearchParams(formData);
            
            fetch(`{{ route('notes.api.data') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.hash !== lastUpdateHash) {
                    updateNotesTable(data.notes);
                    updateStatistics(data.statistics);
                    lastUpdateHash = data.hash;
                    showUpdateNotification();
                }
                updateLastUpdateTime();
                updateStatus('En ligne', 'green');
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour:', error);
                updateStatus('Erreur connexion', 'red');
            })
            .finally(() => {
                showLoading(false);
            });
        }

        function forceRefresh() {
            hideUpdateNotification();
            refreshData();
        }

        function applyFilters() {
            refreshData();
        }

        function updateNotesTable(notes) {
            const tbody = document.getElementById('notes-tbody');
            
            if (notes.length === 0) {
                tbody.innerHTML = `
                    <tr id="no-notes-row">
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Aucune note trouvée.
                        </td>
                    </tr>
                `;
            } else {
                let html = '';
                notes.forEach(note => {
                    html += generateNoteRow(note);
                });
                tbody.innerHTML = html;
            }
            
            // Mettre à jour le compteur
            document.getElementById('notes-count').textContent = notes.length;
        }

        function generateNoteRow(note) {
            const semestreClass = note.semestre === 'S1' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
            
            return `
                <tr class="hover:bg-gray-50" data-note-id="${note.id}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    ${note.eleve.nom} ${note.eleve.prenom}
                                </div>
                                ${note.eleve.classe ? `<div class="text-sm text-gray-500">${note.eleve.classe.nom}</div>` : ''}
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${note.matiere}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${note.type_evaluation}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium ${note.couleur}">
                            ${note.note}/${note.note_sur}
                            <span class="text-gray-500">(${note.note_vingt}/20)</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            ${note.mention}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${note.date_evaluation_formatted}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${semestreClass}">
                            ${note.semestre}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="/notes/${note.id}" class="text-indigo-600 hover:text-indigo-900" title="Voir">👁️</a>
                            <a href="/notes/${note.id}/edit" class="text-yellow-600 hover:text-yellow-900" title="Modifier">✏️</a>
                            <button onclick="deleteNote(${note.id})" class="text-red-600 hover:text-red-900" title="Supprimer">🗑️</button>
                        </div>
                    </td>
                </tr>
            `;
        }

        function updateStatistics(stats) {
            document.getElementById('total-notes').textContent = stats.total;
            document.getElementById('admis-count').textContent = stats.admis;
            document.getElementById('insuffisant-count').textContent = stats.insuffisant;
            document.getElementById('moyenne-generale').textContent = stats.moyenne;
        }

        function deleteNote(noteId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette note ?')) {
                return;
            }

            fetch(`/notes/${noteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Note supprimée avec succès');
                    refreshData();
                } else {
                    alert('Erreur lors de la suppression');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression');
            });
        }

        function showLoading(show) {
            const indicator = document.getElementById('loading-indicator');
            if (show) {
                indicator.classList.remove('hidden');
            } else {
                indicator.classList.add('hidden');
            }
        }

        function showUpdateNotification() {
            const notification = document.getElementById('update-notification');
            notification.classList.remove('hidden');
            setTimeout(() => {
                hideUpdateNotification();
            }, 5000);
        }

        function hideUpdateNotification() {
            const notification = document.getElementById('update-notification');
            notification.classList.add('hidden');
        }

        function showSuccessMessage(message) {
            const messageDiv = document.getElementById('success-message');
            const textSpan = document.getElementById('success-text');
            
            textSpan.textContent = message;
            messageDiv.classList.remove('hidden');
            
            setTimeout(() => {
                messageDiv.classList.add('hidden');
            }, 3000);
        }

        function updateStatus(text, color) {
            const statusText = document.getElementById('status-text');
            const statusDot = document.getElementById('status-dot');
            const statusIndicator = document.getElementById('status-indicator');
            
            statusText.textContent = text;
            
            // Réinitialiser les classes
            statusIndicator.className = 'fixed top-4 right-4 z-50';
            
            switch(color) {
                case 'green':
                    statusIndicator.classList.add('bg-green-500');
                    statusDot.classList.add('animate-pulse');
                    break;
                case 'red':
                    statusIndicator.classList.add('bg-red-500');
                    statusDot.classList.remove('animate-pulse');
                    break;
                case 'gray':
                    statusIndicator.classList.add('bg-gray-500');
                    statusDot.classList.remove('animate-pulse');
                    break;
            }
            
            statusIndicator.classList.add('text-white', 'px-3', 'py-1', 'rounded-full', 'text-sm', 'flex', 'items-center');
        }

        function updateLastUpdateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('fr-FR');
            document.getElementById('last-update').textContent = timeString;
        }

        // Fonction debounce pour éviter trop de requêtes
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Gestion de la visibilité de la page
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Page cachée, réduire la fréquence
                if (autoRefreshEnabled) {
                    clearInterval(intervalId);
                }
            } else {
                // Page visible, reprendre la mise à jour
                if (autoRefreshEnabled) {
                    refreshData(); // Mise à jour immédiate
                    startAutoRefresh();
                }
            }
        });
    </script>
</body>
</html>
