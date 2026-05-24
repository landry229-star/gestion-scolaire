<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departement;

class DepartementController extends Controller
{
    /**
     * Afficher la liste des départements
     */
    public function index()
    {
        $departements = Departement::latest()->get();

        return view(
            'superadmin.academics',
            compact('departement')
        );
    }

    /**
     * Enregistrer un nouveau département
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_departement' => 'required|string|max:255|unique:departements,nom_departement',
        ]);

        Departement::create([
            'nom_departement' => strtoupper($request->nom_departement),
        ]);

        return back()->with(
            'success',
            'Département ajouté avec succès.'
        );
    }

    /**
     * Modifier un département
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_departement' => 'required|string|max:255',
        ]);

        $departement = Departement::findOrFail($id);

        $departement->update([
            'nom_departement' => strtoupper($request->nom_departement),
        ]);

        return back()->with(
            'success',
            'Département modifié avec succès.'
        );
    }

    /**
     * Supprimer un département
     */
    public function destroy($id)
    {
        $departement = Departement::findOrFail($id);

        $departement->delete();

        return back()->with(
            'success',
            'Département supprimé avec succès.'
        );
    }
    public function create()
{
    $departements = Departement::all();

    return view('superadmin.enseignants.index', compact('departements'));
}
}
