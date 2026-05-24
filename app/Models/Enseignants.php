<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Departement;
class Enseignants extends Model
{
    /** @use HasFactory<\Database\Factories\EnseignantsFactory> */
    use HasFactory;
     // Nom de la table dans la base de données
    protected $table = 'enseignants';

    // Nom de la clé primaire
    protected $primaryKey = 'id_enseignant';

    // activer les timestamps
    public $timestamps = true;
    protected $fillable =[
        'id_departement',
        'nom',
        'prenom',
        'sexe',
        'email',
        'grade_enseignant',
        'telephone',
        'photo',
    ];

   /**
     * Relation : Un enseignant peut enseigner plusieurs matières.
     * On passe par la table pivot 'enseignant_matiere'.
     */
    public function matieres()
    {
        return $this->belongsToMany(
            Matieres::class,
            'enseignant_matieres',
            'id_enseignant',
            'id_matiere'
        );
    }
//->withPivot('annee_academique');
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement', 'id_departement');
    }


    /**
     * Optionnel : Si un enseignant est aussi un utilisateur du système (Login)
     */
    public function utilisateurs()
    {
        return $this->hasOne(Utilisateur::class, 'id_enseignant', 'id_enseignant');
    }
}
?>
