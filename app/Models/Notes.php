<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    /** @use HasFactory<\Database\Factories\NotesFactory> */
    use HasFactory;

    protected $table = 'notes';
    protected $primaryKey = 'id_note';
    public $timestamps = true;
protected $fillable = [

    'id_matiere',
    'id_etudiant',

    'valeur_note',

    'session',

    'statut',

    'is_published',

    'published_at',

    'validated_by',

    'validated_at',

    'motif_rejet'
];

    // La note appartient à un étudiant
    public function etudiant()
    {
        return $this->belongsTo(Etudiants::class, 'id_etudiant', 'id_etudiant');
    }

    // La note concerne une matière
    public function matiere()
    {
        return $this->belongsTo(Matieres::class, 'id_matiere', 'id_matiere');
    }


    public function validator() {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
?>
