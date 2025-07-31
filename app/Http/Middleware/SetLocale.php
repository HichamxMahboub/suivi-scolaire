<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Définir la locale
        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
        }

        $response = $next($request);

        // Ajouter des headers de cache pour les assets statiques
        if ($request->is('build/*') || $request->is('*.css') || $request->is('*.js') || $request->is('*.png') || $request->is('*.jpg') || $request->is('*.jpeg') || $request->is('*.gif') || $request->is('*.ico')) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000'); // 1 an
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
        }

        // Headers de cache pour les pages HTML
        if ($request->is('dashboard') || $request->is('classes') || $request->is('eleves')) {
            $response->headers->set('Cache-Control', 'public, max-age=300'); // 5 minutes
        }

        return $response;
    }
}
