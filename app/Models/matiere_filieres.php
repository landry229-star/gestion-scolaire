<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class matiere_filieres extends Model
{
    /** @use HasFactory<\Database\Factories\MatiereFilieresFactory> */
    use HasFactory;
    protected $table = 'matiere_filieres';

    public $incrementing = true; // Si vous avez une clé primaire id_filiere_matiere

    protected $fillable = [
        'id_filiere',
        'id_matiere',

    ];

    /**
     * Relation vers la filière
     */
    public function filieres()
    {
        return $this->belongsTo(Filieres::class, 'id_filiere','id_matiere');
    }

    /**
     * Relation vers la matière
     */
    public function matieres()
    {
        return $this->belongsTo(Matieres::class, 'id_matiere');
    }
}
?>
