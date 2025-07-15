<?php

use Illuminate\Support\Facades\Route;
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
Route::get('/eleves/import', [EleveController::class, 'importForm'])->name('eleves.import.form');
Route::post('/eleves/import', [EleveController::class, 'import'])->name('eleves.import');
Route::get('/eleves/import/modele', [App\Http\Controllers\EleveController::class, 'downloadImportModele'])->name('eleves.import.modele');
Route::get('/eleves/import/modele-excel', [ModeleImportController::class, 'telechargerModeleEleves'])->name('eleves.import.modele.excel');
    Route::get('/eleves/fiche-cvc/modele', [App\Http\Controllers\EleveController::class, 'downloadFicheCvcModele'])->name('eleves.fiche_cvc.modele');
    Route::get('/eleves/stats', [EleveController::class, 'stats'])->name('eleves.stats');
    Route::get('/eleves/export/excel', [\App\Http\Controllers\ExportElevesController::class, 'exportExcel'])->name('eleves.export.excel');
    Route::get('/eleves/export/pdf', [EleveController::class, 'exportPdf'])->name('eleves.export.pdf');
    
    Route::resource('eleves', EleveController::class)->parameters(['eleves' => 'eleve']);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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

Route::get('messages/unread-count', function() {
    return response()->json(['count' => Auth::user()->unreadMessages()->count()]);
})->middleware('auth');

// Suppression des routes notes

// Remplacer la ressource enseignants par encadrants
Route::resource('encadrants', App\Http\Controllers\EncadrantController::class);

// Redirection des anciennes routes enseignants vers encadrants
Route::redirect('/enseignants', '/encadrants', 301);
Route::redirect('/enseignants/create', '/encadrants/create', 301);
Route::redirect('/enseignants/{any}', '/encadrants/{any}', 301)->where('any', '.*');

require __DIR__.'/auth.php';

Route::get('/parametres', function() {
    return view('settings');
})->name('settings')->middleware(['auth']);

Route::post('/eleves/{eleve}/parcours-scolaires', [\App\Http\Controllers\ParcoursScolaireController::class, 'store'])->name('parcours-scolaires.store');
Route::put('/parcours-scolaires/{parcours}', [\App\Http\Controllers\ParcoursScolaireController::class, 'update'])->name('parcours-scolaires.update');
Route::delete('/parcours-scolaires/{parcours}', [\App\Http\Controllers\ParcoursScolaireController::class, 'destroy'])->name('parcours-scolaires.destroy');
