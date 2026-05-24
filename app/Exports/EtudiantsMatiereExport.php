<?php

namespace App\Exports;

use App\Models\Inscriptions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EtudiantsMatiereExport implements FromCollection, WithHeadings, WithMapping
{
    protected $id_filiere;

    // On passe l'ID de la filière pour ne récupérer que les étudiants concernés
    public function __construct($id_filiere) {
        $this->id_filiere = $id_filiere;
    }

    public function collection() {
        return Inscriptions::where('id_filiere', $this->id_filiere)
            ->with('etudiant')
            ->get();
    }

    // Définit les titres des colonnes dans le fichier Excel
    public function headings(): array {
        return [
            "Matricule",
            "Nom",
            "Prénoms",
            "Sexe",
            "Email"
        ];
    }

    // Associe les données aux bonnes colonnes
    public function map($ins): array {
        return [
            $ins->etudiant->matricule,
            strtoupper($ins->etudiant->nom),
            $ins->etudiant->prenom,
            $ins->etudiant->sexe,
            $ins->etudiant->email,
        ];
    }
}
