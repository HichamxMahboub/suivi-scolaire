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
    public function index()
    {
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
                    DB::raw("strftime('%m', created_at) as mois"),
                    DB::raw("strftime('%Y', created_at) as annee"),
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
                ->orWhereNull('nom_parent_1')
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
} 