<!DOCTYPE html>
<html>
<head>
    <title>Tableau de Bord - Temps Réel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">
                    Tableau de Bord 
                    <span class="text-sm text-gray-500">(Temps Réel)</span>
                </h1>
                <p class="text-gray-600 mt-2">Bienvenue dans votre système de suivi scolaire</p>
            </div>
            <div class="flex space-x-2">
                <button onclick="toggleAutoRefresh()" id="auto-refresh-btn" 
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    ⏸️ Pause Auto
                </button>
                <select id="refresh-interval" onchange="updateRefreshInterval()" 
                        class="border border-gray-300 rounded px-3 py-2">
                    <option value="30000" selected>30 secondes</option>
                    <option value="60000">1 minute</option>
                    <option value="120000">2 minutes</option>
                    <option value="300000">5 minutes</option>
                </select>
            </div>
        </div>

        <!-- Dernière mise à jour -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-600">
                    Dernière mise à jour : <span id="last-update">{{ now()->format('H:i:s') }}</span>
                </span>
                <div id="loading-indicator" class="hidden">
                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600"></div>
                </div>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Élèves -->
            <div class="bg-blue-100 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <span class="text-4xl mr-4">👥</span>
                    <div>
                        <p class="text-sm text-gray-600">Élèves</p>
                        <p id="stat-eleves" class="text-3xl font-bold text-blue-600">{{ $stats['eleves'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">
                            Actifs: <span id="stat-eleves-actifs">{{ $stats['eleves_actifs'] ?? 0 }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Classes -->
            <div class="bg-green-100 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <span class="text-4xl mr-4">🎓</span>
                    <div>
                        <p class="text-sm text-gray-600">Classes</p>
                        <p id="stat-classes" class="text-3xl font-bold text-green-600">{{ $stats['classes'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">
                            Actives: <span id="stat-classes-actives">{{ $stats['classes_actives'] ?? 0 }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="bg-purple-100 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <span class="text-4xl mr-4">📩</span>
                    <div>
                        <p class="text-sm text-gray-600">Messages</p>
                        <p id="stat-messages" class="text-3xl font-bold text-purple-600">{{ $stats['messages'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">
                            Non lus: <span id="stat-messages-non-lus">{{ $stats['messages_non_lus'] ?? 0 }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-yellow-100 p-6 rounded-lg shadow">
                <div class="flex items-center">
                    <span class="text-4xl mr-4">📊</span>
                    <div>
                        <p class="text-sm text-gray-600">Notes</p>
                        <p id="stat-notes" class="text-3xl font-bold text-yellow-600">{{ $stats['notes'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500">
                            Moyenne: <span id="stat-notes-moyenne">{{ $stats['notes_moyenne'] ?? '0.00' }}</span>/20
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Graphique des notes par mention -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Répartition des Notes</h3>
                <canvas id="notesChart" width="400" height="200"></canvas>
            </div>

            <!-- Graphique des élèves par classe -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Élèves par Classe</h3>
                <canvas id="elevesChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions Rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('notes.realtime') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center">
                    📊 Notes Temps Réel
                </a>
                <a href="{{ route('eleves.realtime') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center">
                    👥 Élèves Temps Réel
                </a>
                <a href="{{ route('messages.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded text-center">
                    📩 Messages
                </a>
                <a href="{{ route('classes.index') }}" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded text-center">
                    🎓 Gestion Classes
                </a>
            </div>
        </div>

        <!-- Activité récente -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Activité Récente</h3>
            <div id="recent-activity" class="space-y-3">
                <div class="flex items-center text-sm text-gray-600">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                    <span>Chargement de l'activité récente...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript pour la mise à jour en temps réel -->
    <script>
        let autoRefreshEnabled = true;
        let refreshInterval = 30000; // 30 secondes par défaut
        let intervalId = null;
        let notesChart = null;
        let elevesChart = null;

        // Configuration CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
            startAutoRefresh();
            updateLastUpdateTime();
            refreshData(); // Première mise à jour
        });

        function initCharts() {
            // Graphique des notes
            const notesCtx = document.getElementById('notesChart').getContext('2d');
            notesChart = new Chart(notesCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Très Bien', 'Bien', 'Assez Bien', 'Passable', 'Insuffisant'],
                    datasets: [{
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: [
                            '#10B981', // green
                            '#3B82F6', // blue
                            '#F59E0B', // yellow
                            '#F97316', // orange
                            '#EF4444'  // red
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    height: 200
                }
            });

            // Graphique des élèves par classe
            const elevesCtx = document.getElementById('elevesChart').getContext('2d');
            elevesChart = new Chart(elevesCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Nombre d\'élèves',
                        data: [],
                        backgroundColor: '#3B82F6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    height: 200,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
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
            
            fetch('/api/dashboard/data', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                updateStatistics(data.statistics);
                updateCharts(data.charts);
                updateRecentActivity(data.recent_activity);
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

        function updateStatistics(stats) {
            document.getElementById('stat-eleves').textContent = stats.eleves;
            document.getElementById('stat-eleves-actifs').textContent = stats.eleves_actifs;
            document.getElementById('stat-classes').textContent = stats.classes;
            document.getElementById('stat-classes-actives').textContent = stats.classes_actives;
            document.getElementById('stat-messages').textContent = stats.messages;
            document.getElementById('stat-messages-non-lus').textContent = stats.messages_non_lus;
            document.getElementById('stat-notes').textContent = stats.notes;
            document.getElementById('stat-notes-moyenne').textContent = stats.notes_moyenne;
        }

        function updateCharts(chartData) {
            // Mettre à jour le graphique des notes
            if (chartData.notes_mentions) {
                notesChart.data.datasets[0].data = [
                    chartData.notes_mentions.tres_bien,
                    chartData.notes_mentions.bien,
                    chartData.notes_mentions.assez_bien,
                    chartData.notes_mentions.passable,
                    chartData.notes_mentions.insuffisant
                ];
                notesChart.update();
            }

            // Mettre à jour le graphique des élèves par classe
            if (chartData.eleves_par_classe) {
                elevesChart.data.labels = chartData.eleves_par_classe.labels;
                elevesChart.data.datasets[0].data = chartData.eleves_par_classe.data;
                elevesChart.update();
            }
        }

        function updateRecentActivity(activities) {
            const container = document.getElementById('recent-activity');
            container.innerHTML = '';

            if (activities.length === 0) {
                container.innerHTML = '<div class="text-gray-500 text-sm">Aucune activité récente</div>';
                return;
            }

            activities.forEach(activity => {
                const div = document.createElement('div');
                div.className = 'flex items-center text-sm text-gray-600';
                div.innerHTML = `
                    <span class="w-2 h-2 ${activity.color} rounded-full mr-3"></span>
                    <span class="flex-1">${activity.message}</span>
                    <span class="text-xs text-gray-400">${activity.time}</span>
                `;
                container.appendChild(div);
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
