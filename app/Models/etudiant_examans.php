<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class etudiant_examans extends Model
{
    /** @use HasFactory<\Database\Factories\EtudiantExamansFactory> */
    use HasFactory;
    protected $table = 'etudiant_examen';
    protected $primaryKey = 'id_etudiant_examen';
    public $timestamps = true;

    protected $fillable = [
        'id_etudiant',
        'id_examen',
        'id_matiere',
        'note_examen',
        'anonymat', // Utile pour les examens à correction anonyme
        'statut_presence', // ex: Présent, Absent, Justifié
    ];

    /**
     * Relation : L'examen concerne un étudiant.
     */
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'id_etudiant');
    }

    /**
     * Relation : Lien vers la session d'examen (ex: Session Normale 2026).
     */
    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id_examen');
    }

    /**
     * Relation : Lien vers la matière évaluée.
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class, 'id_matiere');
    }
}
?>
