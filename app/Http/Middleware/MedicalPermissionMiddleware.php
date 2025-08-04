<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MedicalPermissionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Seuls les admins et le personnel médical peuvent accéder
        if (!$user->canViewMedicalInfo()) {
            abort(403, 'Accès refusé. Vous n\'avez pas les permissions pour voir les informations médicales.');
        }

        return $next($request);
    }
}
