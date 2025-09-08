<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // Vérifier que la langue est supportée
        $supportedLocales = ['fr', 'ar'];

        if (!in_array($locale, $supportedLocales)) {
            $locale = 'fr'; // Langue par défaut
        }

        // Sauvegarder la langue en session
        session()->put('locale', $locale);

        // Retourner une réponse JSON pour AJAX
        return response()->json([
            'success' => true,
            'locale' => $locale,
            'message' => 'Langue changée avec succès'
        ]);
    }
}
