<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function createNote()
{
    $etudiants = Etudiants::all();
    $matieres = Matieres::all();

    return view(
        'superadmin.notes.create',
        compact('etudiants', 'matieres')
    );
}
public function validateNotes($id_matiere)
{
    // Valide toutes les notes associées à cette matière
    Note::where('id_matiere', $id_matiere)
        ->where('is_validated', false)
        ->update(['is_validated' => true]);

    return redirect()->back()->with('success', 'Les notes ont été validées et certifiées avec succès.');
}

public function publishNotes($id_matiere)
{
    // Publie toutes les notes validées de cette matière pour les rendre visibles aux étudiants
    Note::where('id_matiere', $id_matiere)
        ->where('is_validated', true)
        ->where('is_published', false)
        ->update(['is_published' => true]);

    return redirect()->back()->with('success', 'Les notes ont été publiées officiellement pour les étudiants.');
}

}
