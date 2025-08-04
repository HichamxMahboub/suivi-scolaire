<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Élèves - Temps Réel</title>
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
                Gestion des Élèves 
                <span class="text-sm text-gray-500">(Temps Réel)</span>
            </h1>
            <div class="flex space-x-2">
                <button onclick="toggleAutoRefresh()" id="auto-refresh-btn" 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    ⏸️ Pause Auto
                </button>
                <a href="{{ route('eleves.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Ajouter un Élève
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
                        <option value="15000">15 secondes</option>
                        <option value="30000" selected>30 secondes</option>
                        <option value="60000">1 minute</option>
                        <option value="120000">2 minutes</option>
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
            <form id="filter-form" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Recherche</label>
                    <input type="text" name="search" id="search" 
                           placeholder="Nom, prénom, CNE..." 
                           class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <div>
                    <label for="classe" class="block text-sm font-medium text-gray-700">Classe</label>
                    <select name="classe" id="classe" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $classe)
                            <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" id="statut" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Tous</option>
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
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
                <a href="{{ route('eleves.import.form') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    📁 Importer Excel
                </a>
                <a href="{{ route('eleves.export.excel') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    📊 Exporter Excel
                </a>
                <a href="{{ route('eleves.stats') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    📈 Statistiques
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
                    <div id="total-eleves" class="text-2xl font-bold text-blue-600">{{ $eleves->count() }}</div>
                    <div class="text-gray-600">Total Élèves</div>
                </div>
                <div>
                    <div id="eleves-actifs" class="text-2xl font-bold text-green-600">{{ $eleves->where('statut', 'actif')->count() }}</div>
                    <div class="text-gray-600">Actifs</div>
                </div>
                <div>
                    <div id="eleves-inactifs" class="text-2xl font-bold text-orange-600">{{ $eleves->where('statut', 'inactif')->count() }}</div>
                    <div class="text-gray-600">Inactifs</div>
                </div>
                <div>
                    <div id="total-classes" class="text-2xl font-bold text-purple-600">{{ $classes->count() }}</div>
                    <div class="text-gray-600">Classes</div>
                </div>
            </div>
        </div>

        <!-- Liste des élèves -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Liste des Élèves</h3>
                    <div class="flex items-center space-x-2">
                        <div id="loading-indicator" class="hidden">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600"></div>
                        </div>
                        <span class="text-sm text-gray-500">
                            <span id="eleves-count">{{ $eleves->count() }}</span> élèves affichés
                        </span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="eleves-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Élève
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    CNE
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date de naissance
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Classe
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="eleves-tbody" class="bg-white divide-y divide-gray-200">
                            @forelse($eleves as $eleve)
                                <tr class="hover:bg-gray-50" data-eleve-id="{{ $eleve->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $eleve->nom }} {{ $eleve->prenom }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $eleve->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $eleve->cne }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $eleve->date_naissance ? $eleve->date_naissance->format('d/m/Y') : 'Non renseignée' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($eleve->classe)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $eleve->classe->nom }}
                                            </span>
                                        @else
                                            <span class="text-gray-500">Aucune classe</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $eleve->statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($eleve->statut) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('eleves.show', $eleve) }}" 
                                               class="text-indigo-600 hover:text-indigo-900" title="Voir">
                                                👁️
                                            </a>
                                            <a href="{{ route('eleves.edit', $eleve) }}" 
                                               class="text-yellow-600 hover:text-yellow-900" title="Modifier">
                                                ✏️
                                            </a>
                                            <button onclick="deleteEleve({{ $eleve->id }})" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Supprimer">
                                                🗑️
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="no-eleves-row">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Aucun élève trouvé.
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
        let refreshInterval = 30000; // 30 secondes par défaut
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
            document.getElementById('classe').addEventListener('change', applyFilters);
            document.getElementById('statut').addEventListener('change', applyFilters);
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
            
            fetch(`/api/eleves/data?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.hash !== lastUpdateHash) {
                    updateElevesTable(data.eleves);
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

        function updateElevesTable(eleves) {
            const tbody = document.getElementById('eleves-tbody');
            
            if (eleves.length === 0) {
                tbody.innerHTML = `
                    <tr id="no-eleves-row">
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun élève trouvé.
                        </td>
                    </tr>
                `;
            } else {
                let html = '';
                eleves.forEach(eleve => {
                    html += generateEleveRow(eleve);
                });
                tbody.innerHTML = html;
            }
            
            // Mettre à jour le compteur
            document.getElementById('eleves-count').textContent = eleves.length;
        }

        function generateEleveRow(eleve) {
            const statutClass = eleve.statut === 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
            const classeHtml = eleve.classe ? 
                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${eleve.classe.nom}</span>` :
                '<span class="text-gray-500">Aucune classe</span>';
            
            return `
                <tr class="hover:bg-gray-50" data-eleve-id="${eleve.id}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    ${eleve.nom} ${eleve.prenom}
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${eleve.email || ''}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${eleve.cne || ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${eleve.date_naissance_formatted || 'Non renseignée'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${classeHtml}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statutClass}">
                            ${eleve.statut.charAt(0).toUpperCase() + eleve.statut.slice(1)}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="/eleves/${eleve.id}" class="text-indigo-600 hover:text-indigo-900" title="Voir">👁️</a>
                            <a href="/eleves/${eleve.id}/edit" class="text-yellow-600 hover:text-yellow-900" title="Modifier">✏️</a>
                            <button onclick="deleteEleve(${eleve.id})" class="text-red-600 hover:text-red-900" title="Supprimer">🗑️</button>
                        </div>
                    </td>
                </tr>
            `;
        }

        function updateStatistics(stats) {
            document.getElementById('total-eleves').textContent = stats.total;
            document.getElementById('eleves-actifs').textContent = stats.actifs;
            document.getElementById('eleves-inactifs').textContent = stats.inactifs;
            document.getElementById('total-classes').textContent = stats.classes;
        }

        function deleteEleve(eleveId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élève ?')) {
                return;
            }

            fetch(`/eleves/${eleveId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Élève supprimé avec succès');
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

        // Fonctions utilitaires (similaires à celles des notes)
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
                if (autoRefreshEnabled) {
                    clearInterval(intervalId);
                }
            } else {
                if (autoRefreshEnabled) {
                    refreshData();
                    startAutoRefresh();
                }
            }
        });
    </script>
</body>
</html>
