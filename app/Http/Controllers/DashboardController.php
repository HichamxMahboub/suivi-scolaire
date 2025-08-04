<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\User;
use App\Models\EtatSante;
use App\Models\Classe;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Vérifier si c'est une requête pour la version temps réel
        if ($request->has('realtime')) {
            return $this->realtimeIndex();
        }

        // Utiliser le cache pour les statistiques (5 minutes)
        $cacheKey = 'dashboard_stats_' . auth()->id();
        $stats = Cache::remember($cacheKey, 300, function () {
            return [
                'eleves' => Eleve::count(),
                'classes' => Classe::count(),
                'messages' => Message::count(),
                'total_eleves' => Eleve::count(),
                'total_users' => User::count(),
                'total_notes' => Eleve::count(), // This line was removed as per the edit hint
                'eleves_avec_dossier_medical' => EtatSante::count(),
                'total_classes' => Classe::count(),
                'classes_actives' => Classe::where('active', true)->count(),
            ];
        });

        // Suppression des stats et variables liées aux notes

        // Cache pour les répartitions
        $repartition_niveaux = Cache::remember('dashboard_niveaux', 300, function () {
            return Eleve::select('niveau_scolaire', DB::raw('count(*) as total'))
                ->whereNotNull('niveau_scolaire')
                ->groupBy('niveau_scolaire')
                ->orderBy('niveau_scolaire')
                ->get();
        });

        // Élèves récemment ajoutés (pas de cache car données récentes)
        $eleves_recents = Eleve::where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Cache pour les autres répartitions
        $repartition_annees = Cache::remember('dashboard_annees', 300, function () {
            return Eleve::select('annee_entree', DB::raw('count(*) as total'))
                ->whereNotNull('annee_entree')
                ->groupBy('annee_entree')
                ->orderBy('annee_entree', 'desc')
                ->limit(5)
                ->get();
        });

        $top_educateurs = Cache::remember('dashboard_educateurs', 300, function () {
            return Eleve::select('educateur_responsable', DB::raw('count(*) as total'))
                ->whereNotNull('educateur_responsable')
                ->groupBy('educateur_responsable')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get();
        });

        $eleves_par_mois = Cache::remember('dashboard_mois', 300, function () {
            return Eleve::select(
<<<<<<< HEAD
                    DB::raw('MONTH(created_at) as mois'),
                    DB::raw('YEAR(created_at) as annee'),
=======
                    DB::raw("strftime('%m', created_at) as mois"),
                    DB::raw("strftime('%Y', created_at) as annee"),
>>>>>>> 9c53c191ecec3c6c2106528e2196200d297a3088
                    DB::raw('count(*) as total')
                )
                ->where('created_at', '>=', Carbon::now()->subMonths(6))
                ->groupBy('mois', 'annee')
                ->orderBy('annee', 'desc')
                ->orderBy('mois', 'desc')
                ->get();
        });

        $eleves_incomplets = Cache::remember('dashboard_incomplets', 300, function () {
            return Eleve::whereNull('date_naissance')
                ->orWhereNull('niveau_scolaire')
<<<<<<< HEAD
                ->orWhereNull('nom_tuteur')
=======
                ->orWhereNull('nom_parent_1')
>>>>>>> 9c53c191ecec3c6c2106528e2196200d297a3088
                ->count();
        });

        $classes_recentes = Cache::remember('dashboard_classes_recentes', 300, function () {
            return Classe::where('created_at', '>=', Carbon::now()->subDays(30))
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });

        $classes_plus_remplies = Cache::remember('dashboard_classes_remplies', 300, function () {
            return Classe::withCount('eleves')
                ->orderBy('eleves_count', 'desc')
                ->limit(5)
                ->get();
        });

        // Alertes
        $alertes = [];
        
        if ($eleves_incomplets > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "{$eleves_incomplets} élève(s) avec des informations incomplètes",
                'route' => route('eleves.index')
            ];
        }

        if ($stats['total_eleves'] == 0) {
            $alertes[] = [
                'type' => 'info',
                'message' => 'Aucun élève enregistré. Commencez par importer des élèves.',
                'route' => route('eleves.import.form')
            ];
        }

        return view('dashboard', compact(
            'stats',
            'repartition_niveaux',
            'eleves_recents',
            'repartition_annees',
            'top_educateurs',
            'eleves_par_mois',
            'eleves_incomplets',
            'classes_recentes',
            'classes_plus_remplies',
            'alertes'
        ));
    }

    /**
     * Version temps réel du dashboard
     */
    public function realtimeIndex()
    {
        // Calculer les statistiques directement (pas de cache pour le temps réel)
        $stats = [
            'eleves' => Eleve::count(),
            'eleves_actifs' => Eleve::whereNotNull('classe_id')->count(),
            'classes' => Classe::count(),
            'classes_actives' => Classe::where('active', true)->count(),
            'messages' => Message::count(),
            'messages_non_lus' => Message::where('lu', false)->count(),
            'notes' => \App\Models\Note::count(),
            'notes_moyenne' => number_format(\App\Models\Note::avg('note_vingt') ?? 0, 2),
        ];

        return view('dashboard-realtime', compact('stats'));
    }

    /**
     * API endpoint pour récupérer les données du dashboard en temps réel
     */
    public function apiData()
    {
        // Statistiques principales
        $statistics = [
            'eleves' => Eleve::count(),
            'eleves_actifs' => Eleve::whereNotNull('classe_id')->count(),
            'classes' => Classe::count(),
            'classes_actives' => Classe::where('active', true)->count(),
            'messages' => Message::count(),
            'messages_non_lus' => Message::where('lu', false)->count(),
            'notes' => \App\Models\Note::count(),
            'notes_moyenne' => number_format(\App\Models\Note::avg('note_vingt') ?? 0, 2),
        ];

        // Données pour les graphiques
        $charts = [
            'notes_mentions' => [
                'tres_bien' => \App\Models\Note::where('note_vingt', '>=', 16)->count(),
                'bien' => \App\Models\Note::where('note_vingt', '>=', 14)->where('note_vingt', '<', 16)->count(),
                'assez_bien' => \App\Models\Note::where('note_vingt', '>=', 12)->where('note_vingt', '<', 14)->count(),
                'passable' => \App\Models\Note::where('note_vingt', '>=', 10)->where('note_vingt', '<', 12)->count(),
                'insuffisant' => \App\Models\Note::where('note_vingt', '<', 10)->count(),
            ],
            'eleves_par_classe' => [
                'labels' => Classe::withCount('eleves')->orderBy('nom')->pluck('nom'),
                'data' => Classe::withCount('eleves')->orderBy('nom')->pluck('eleves_count'),
            ]
        ];

        // Activité récente
        $recent_activity = [];

        // Dernières notes ajoutées
        $recentNotes = \App\Models\Note::with(['eleve'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentNotes as $note) {
            $recent_activity[] = [
                'message' => "Note ajoutée pour {$note->eleve->prenom} {$note->eleve->nom} en {$note->matiere}",
                'time' => $note->created_at->diffForHumans(),
                'color' => 'bg-blue-500'
            ];
        }

        // Derniers élèves ajoutés
        $recentEleves = Eleve::orderBy('created_at', 'desc')->limit(2)->get();
        foreach ($recentEleves as $eleve) {
            $recent_activity[] = [
                'message' => "Élève ajouté : {$eleve->prenom} {$eleve->nom}",
                'time' => $eleve->created_at->diffForHumans(),
                'color' => 'bg-green-500'
            ];
        }

        // Derniers messages
        $recentMessages = Message::orderBy('created_at', 'desc')->limit(2)->get();
        foreach ($recentMessages as $message) {
            $recent_activity[] = [
                'message' => "Nouveau message : " . \Str::limit($message->objet, 30),
                'time' => $message->created_at->diffForHumans(),
                'color' => 'bg-purple-500'
            ];
        }

        // Trier par date
        usort($recent_activity, function($a, $b) {
            return strcmp($b['time'], $a['time']);
        });

        return response()->json([
            'statistics' => $statistics,
            'charts' => $charts,
            'recent_activity' => array_slice($recent_activity, 0, 5),
            'timestamp' => now()->toISOString()
        ]);
    }
} 