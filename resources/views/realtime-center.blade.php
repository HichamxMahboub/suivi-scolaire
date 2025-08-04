<!DOCTYPE html>
<html>
<head>
    <title>Centre de Contrôle Temps Réel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="max-w-4xl mx-auto">
            <!-- En-tête -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    🚀 Centre de Contrôle Temps Réel
                </h1>
                <p class="text-lg text-gray-600">
                    Accédez à toutes les fonctionnalités avec mise à jour automatique
                </p>
            </div>

            <!-- Navigation vers les pages normales -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">🔗 Navigation Classique</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="{{ route('dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center">
                        📊 Dashboard Standard
                    </a>
                    <a href="{{ route('notes.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center">
                        📝 Notes Standard
                    </a>
                    <a href="{{ route('eleves.index') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded text-center">
                        👥 Élèves Standard
                    </a>
                </div>
            </div>

            <!-- Fonctionnalités temps réel -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 text-white mb-8">
                <h2 class="text-2xl font-bold mb-6 text-center">⚡ Fonctionnalités Temps Réel</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Dashboard Temps Réel -->
                    <div class="bg-white bg-opacity-20 rounded-lg p-6 hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-center">
                            <div class="text-4xl mb-4">📈</div>
                            <h3 class="text-xl font-bold mb-2">Dashboard Temps Réel</h3>
                            <p class="text-sm mb-4 opacity-90">
                                Statistiques et graphiques mis à jour automatiquement
                            </p>
                            <a href="{{ route('dashboard.realtime') }}" 
                               class="bg-white text-blue-600 px-4 py-2 rounded font-semibold hover:bg-gray-100 transition-colors">
                                Accéder
                            </a>
                        </div>
                    </div>

                    <!-- Notes Temps Réel -->
                    <div class="bg-white bg-opacity-20 rounded-lg p-6 hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-center">
                            <div class="text-4xl mb-4">📊</div>
                            <h3 class="text-xl font-bold mb-2">Notes Temps Réel</h3>
                            <p class="text-sm mb-4 opacity-90">
                                Gestion des notes avec mise à jour live
                            </p>
                            <a href="{{ route('notes.realtime') }}" 
                               class="bg-white text-blue-600 px-4 py-2 rounded font-semibold hover:bg-gray-100 transition-colors">
                                Accéder
                            </a>
                        </div>
                    </div>

                    <!-- Élèves Temps Réel -->
                    <div class="bg-white bg-opacity-20 rounded-lg p-6 hover:bg-opacity-30 transition-all duration-300">
                        <div class="text-center">
                            <div class="text-4xl mb-4">👥</div>
                            <h3 class="text-xl font-bold mb-2">Élèves Temps Réel</h3>
                            <p class="text-sm mb-4 opacity-90">
                                Liste des élèves avec actualisation automatique
                            </p>
                            <a href="{{ route('eleves.realtime') }}" 
                               class="bg-white text-blue-600 px-4 py-2 rounded font-semibold hover:bg-gray-100 transition-colors">
                                Accéder
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités temps réel -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">✨ Fonctionnalités Temps Réel</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">🔄 Mise à jour automatique</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Actualisation périodique des données</li>
                            <li>• Notifications de nouveaux changements</li>
                            <li>• Contrôle de la fréquence de mise à jour</li>
                            <li>• Indicateur de statut de connexion</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-2">⚙️ Options avancées</h4>
                        <ul class="text-sm text-gray-600 space-y-1">
                            <li>• Pause et reprise automatique</li>
                            <li>• Filtrage en temps réel</li>
                            <li>• Actions AJAX (suppression, modification)</li>
                            <li>• Gestion de la visibilité de page</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Conseils d'utilisation -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-yellow-800 mb-3">💡 Conseils d'utilisation</h2>
                <div class="text-sm text-yellow-700 space-y-2">
                    <p><strong>Pour les Notes :</strong> Utilisez les filtres pour voir des données spécifiques mises à jour en temps réel.</p>
                    <p><strong>Pour les Élèves :</strong> Les changements de statut et de classe sont reflétés instantanément.</p>
                    <p><strong>Pour le Dashboard :</strong> Les graphiques et statistiques se mettent à jour automatiquement.</p>
                    <p><strong>Performance :</strong> Les pages temps réel consomment plus de ressources - utilisez les selon vos besoins.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
