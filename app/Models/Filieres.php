<?php

namespace App\Models;
use App\Models\Departement;
use App\Models\Matieres;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filieres extends Model
{
    /** @use HasFactory<\Database\Factories\FilieresFactory> */
    use HasFactory;
     protected $table = 'filieres';
    protected $primaryKey = 'id_filiere';
    public $timestamps = true;

    protected $fillable = [
        'id_departement',
        'nom_filiere',     // ex: Informatique de Gestion
        'code_filiere',    // ex: IG
        'description',
    ];

    /**
     * Relation : Une filière appartient à un département.
     */
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement');
    }

    /**
     * Relation : Une filière contient plusieurs inscriptions d'étudiants.
     */
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_filiere');
    }

    /**
     * Relation : Une filière possède plusieurs matières.
     * (Si tu as une table de liaison filiere_matiere)
     */
    public function matieres()
    {
        return $this->belongsToMany(Matieres::class, 'matiere_filieres', 'id_filiere', 'id_matiere');
    }
}
