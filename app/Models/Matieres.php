<?php

namespace App\Models;
use App\Models\Departement;
use App\Models\Semestres;
use App\Models\Filieres;
use App\Models\Notes;
use App\Models\Enseignants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matieres extends Model
{
    /** @use HasFactory<\Database\Factories\MatieresFactory> */
    use HasFactory;
protected $table = 'matieres';
    protected $primaryKey = 'id_matiere';
    public $timestamps = true;

    // Ajout de id_departement et id_filiere pour autoriser la création en base
    protected $fillable = [
        'id_departement',
        'id_filiere',
        'id_semestre',
        'id_examen',
        'nom_matiere',
        'code_matiere',
        'credit_matiere',
    ];

    /**
     * Relation : Une matière appartient à un département.
     */
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement', 'id_departement');
    }
    /**
     * Relation : Une matière appartient à un semestre.
     */
    public function semestre()
    {
        return $this->belongsTo(Semestres::class, 'id_semestre', 'id_semestre');
    }
   public function filiere()
    {
        return $this->belongsTo(Filieres::class, 'id_filiere', 'id_filiere');
    }

// Pour récupérer les notes de cette matière
public function notes()
{
    return $this->hasMany(Notes::class, 'id_matiere', 'id_matiere');
}



    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id_examen', 'id_examen');
    }

    public function filieres()
    {
        return $this->belongsToMany(Filieres::class, 'matiere_filieres', 'id_matiere', 'id_filiere');
    }
    public function enseignants()
{
    return $this->belongsToMany(Enseignants::class, 'enseignant_matieres', 'id_matiere', 'id_enseignant');
}

}
?>
