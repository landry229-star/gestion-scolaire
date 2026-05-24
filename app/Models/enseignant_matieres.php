<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enseignant_matieres extends Model
{
    /** @use HasFactory<\Database\Factories\EnseignantMatieresFactory> */
    use HasFactory;
    protected $table = 'enseignant_matieres';

    // Si votre table pivot a des clés primaires auto-incrémentées
    public $incrementing = true;

    protected $fillable = [
        'id_enseignant',
        'id_matiere',
      //  'annee_academique', // Optionnel : pour savoir qui enseignait quoi quelle année
    ];

    /**
     * Relation vers l'enseignant
     */
    public function enseignants()
    {
        return $this->belongsTo(Enseignants::class, 'id_enseignant');
    }

    /**
     * Relation vers la matière
     */
    public function matiere()
    {
        return $this->belongsTo(Matieres::class, 'id_matiere');
    }
}
?>
