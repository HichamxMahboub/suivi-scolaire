<?php

namespace App\Http\Controllers;

use App\Exports\ModeleImportElevesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ModeleImportController extends Controller
{
    public function telechargerModeleEleves()
    {
        $nomFichier = 'modele_import_eleves_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new ModeleImportElevesExport(), $nomFichier);
    }
} 