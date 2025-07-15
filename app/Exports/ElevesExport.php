<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ElevesExport implements FromView
{
    protected $eleves;

    public function __construct($eleves)
    {
        $this->eleves = $eleves;
    }

    public function view(): View
    {
        return view('eleves.export_excel', [
            'eleves' => $this->eleves
        ]);
    }
} 