<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RapportStageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ModeleImportController;

// Route de changement de langue
Route::post('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// Routes pour les élèves
Route::middleware('auth')->group(function () {
    // Routes pour l'import Excel (doivent être AVANT la ressource)
    Route::get('/eleves/import', function () { return view('eleves.import_simple'); })->name('eleves.import.form');
    Route::get('/eleves/import-simple', function () { return view('eleves.import_simple'); })->name('eleves.import.simple');
    Route::post('/eleves/import', [EleveController::class, 'import'])->name('eleves.import');
    Route::post('/eleves/import-direct', [EleveController::class, 'importDirect'])->name('eleves.import.direct');
    Route::get('/eleves/import/modele', [App\Http\Controllers\EleveController::class, 'downloadImportModele'])->name('eleves.import.modele');
    Route::get('/eleves/import/modele-excel', [ModeleImportController::class, 'telechargerModeleEleves'])->name('eleves.import.modele.excel');
    Route::get('/eleves/fiche-cvc/modele', [App\Http\Controllers\EleveController::class, 'downloadFicheCvcModele'])->name('eleves.fiche_cvc.modele');
    Route::get('/eleves/stats', [EleveController::class, 'stats'])->name('eleves.stats');
    Route::get('/eleves/export/excel', [\App\Http\Controllers\ExportElevesController::class, 'exportExcel'])->name('eleves.export.excel');
    Route::get('/eleves/export/pdf', [EleveController::class, 'exportPdf'])->name('eleves.export.pdf');

    // Route pour la version temps réel des élèves
    Route::get('/eleves/realtime', function (Request $request) {
        return app(EleveController::class)->index($request->merge(['realtime' => true]));
    })->name('eleves.realtime');

    // API pour les données des élèves en temps réel
    Route::get('/api/eleves/data', [EleveController::class, 'apiData'])->name('eleves.api.data');

    // Routes pour les mises à jour spécifiques des élèves
    Route::patch('/eleves/{eleve}/update-basic', [EleveController::class, 'updateBasic'])->name('eleves.update.basic');
    Route::patch('/eleves/{eleve}/update-contact', [EleveController::class, 'updateContact'])->name('eleves.update.contact');
    Route::patch('/eleves/{eleve}/update-medical', [EleveController::class, 'updateMedical'])->name('eleves.update.medical');
    Route::patch('/eleves/{eleve}/update-photo', [EleveController::class, 'updatePhoto'])->name('eleves.update.photo');
    Route::get('/eleves/{eleve}/profile-complete', [EleveController::class, 'profileComplete'])->name('eleves.profile.complete');
    Route::get('/eleves/{eleve}/export-pdf', [EleveController::class, 'exportToPdf'])->name('eleves.export.pdf');

    Route::resource('eleves', EleveController::class)->parameters(['eleves' => 'eleve']);
});

Route::get('/', function () {
    return view('welcome');
});

// Centre de contrôle temps réel
Route::get('/realtime-center', function () {
    return view('realtime-center');
})->middleware('auth')->name('realtime.center');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route pour la version temps réel du dashboard
Route::get('/dashboard/realtime', function (Request $request) {
    return app(DashboardController::class)->index($request->merge(['realtime' => true]));
})->middleware(['auth', 'verified'])->name('dashboard.realtime');

// API pour les données du dashboard en temps réel
Route::get('/api/dashboard/data', [DashboardController::class, 'apiData'])->middleware('auth')->name('dashboard.api.data');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('users', UserController::class)->middleware('auth');

// Routes pour les classes
Route::resource('classes', ClasseController::class)
    ->parameters(['classes' => 'classe'])
    ->middleware('auth');
Route::post('/classes/{classe}/assigner-eleves', [ClasseController::class, 'assignerEleves'])->name('classes.assigner-eleves');
Route::post('/classes/{classe}/retirer-eleves', [ClasseController::class, 'retirerEleves'])->name('classes.retirer-eleves');

// Routes pour les messages
Route::middleware('auth')->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::patch('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::post('/messages/mark-read', [MessageController::class, 'markMultipleAsRead'])->name('messages.mark-read');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/eleve/{eleve}', [MessageController::class, 'byEleve'])->name('messages.by-eleve');
    Route::get('/messages/stats', [MessageController::class, 'stats'])->name('messages.stats');
    Route::post('/messages/{message}/quick-reply', [App\Http\Controllers\MessageController::class, 'quickReply'])->name('messages.quick-reply');
    Route::post('messages/{message}/favorite', [App\Http\Controllers\MessageController::class, 'toggleFavorite'])->name('messages.favorite')->middleware('auth');
    Route::post('messages/{message}/archive', [App\Http\Controllers\MessageController::class, 'archive'])->name('messages.archive')->middleware('auth');
    Route::post('messages/{message}/restore', [App\Http\Controllers\MessageController::class, 'restoreArchive'])->name('messages.restore')->middleware('auth');
});

Route::get('messages/unread-count', function () {
    return response()->json(['count' => Auth::user()->unreadMessages()->count()]);
})->middleware('auth');

// Routes pour les notes (après les élèves)
Route::middleware('auth')->group(function () {
    Route::get('/notes/statistiques', [NoteController::class, 'statistiques'])->name('notes.statistiques');
    Route::get('/eleves/{eleve}/bulletin', [NoteController::class, 'bulletin'])->name('notes.bulletin');

    // Routes pour la navigation par type d'établissement
    Route::get('/notes/{type_etablissement}/niveaux', [NoteController::class, 'niveaux'])->name('notes.niveaux');
    Route::get('/notes/{type_etablissement}/classe/{classe}/eleves', [NoteController::class, 'eleves'])->name('notes.eleves');

    // Route pour créer une note pour un élève spécifique (AVANT la route resource)
    Route::get('/notes/create/eleve/{eleve_id}/type/{type_etablissement}', [NoteController::class, 'createForEleve'])->name('notes.create.eleve');

    // Route pour la version temps réel des notes
    Route::get('/notes/realtime', function (Request $request) {
        return app(NoteController::class)->index($request->merge(['realtime' => true]));
    })->name('notes.realtime');

    // API pour les données en temps réel
    Route::get('/api/notes/data', [NoteController::class, 'apiData'])->name('notes.api.data');

    // Route resource APRÈS les routes spécifiques pour éviter les conflits
    Route::resource('notes', NoteController::class);
});

// Route de test temporaire SANS middleware auth pour diagnostiquer
Route::get('/test-note-create/{eleve_id}/{type_etablissement}', function ($eleveId, $typeEtablissement) {
    try {
        $controller = new App\Http\Controllers\NoteController();
        $request = new Illuminate\Http\Request();
        return $controller->createForEleve($request, $typeEtablissement, $eleveId);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->name('test.note.create');

// Remplacer la ressource enseignants par encadrants
Route::resource('encadrants', App\Http\Controllers\EncadrantController::class);

// Routes supplémentaires pour la gestion des élèves par encadrant
Route::post('encadrants/{encadrant}/add-student', [App\Http\Controllers\EncadrantController::class, 'addStudent'])->name('encadrants.add-student');
Route::delete('encadrants/{encadrant}/remove-student', [App\Http\Controllers\EncadrantController::class, 'removeStudent'])->name('encadrants.remove-student');

// Redirection des anciennes routes enseignants vers encadrants
Route::redirect('/enseignants', '/encadrants', 301);
Route::redirect('/enseignants/create', '/encadrants/create', 301);
Route::redirect('/enseignants/{any}', '/encadrants/{any}', 301)->where('any', '.*');

require __DIR__.'/auth.php';

Route::get('/parametres', function () {
    return view('settings');
})->name('settings')->middleware(['auth']);

Route::post('/eleves/{eleve}/parcours-scolaires', [\App\Http\Controllers\ParcoursScolaireController::class, 'store'])->name('parcours-scolaires.store');
Route::put('/parcours-scolaires/{parcours}', [\App\Http\Controllers\ParcoursScolaireController::class, 'update'])->name('parcours-scolaires.update');
Route::delete('/parcours-scolaires/{parcours}', [\App\Http\Controllers\ParcoursScolaireController::class, 'destroy'])->name('parcours-scolaires.destroy');

// Rapport de stage (DOCX) — accessible sans auth; ajoutez middleware si nécessaire
Route::get('/rapport-stage.docx', [RapportStageController::class, 'generate'])->name('rapport.stage.docx');
