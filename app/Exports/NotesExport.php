<?php

namespace App\Exports;

use App\Models\Notes;
use Maatwebsite\Excel\Concerns\FromCollection;

class NotesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
     public function collection()
    {
        return Notes::with([
            'etudiant',
            'matiere'
        ])->get();
    }
}
