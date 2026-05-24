<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    /** @use HasFactory<\Database\Factories\ExamenFactory> */
    use HasFactory;
    protected $table = 'examens';
    protected $primaryKey = 'id_examen';

    protected $fillable = [
        'id_semestre',
        'nom_session',    // ex: "Session Normale", "Session de Rattrapage"
        'annee_universitaire', // ex: "2025-2026"
        'date_debut',
        'date_fin',
        'statut_cloture', // Booléen : pour empêcher la modification des notes après délibération
    ];

    /**
     * Relation : Un examen appartient à un semestre précis.
     */
    public function semestre()
    {
        return $this->belongsTo(Semestre::class, 'id_semestre');
    }

    /**
     * Relation : Un examen contient plusieurs participations d'étudiants.
     */
    public function participations()
    {
        return $this->hasMany(EtudiantExamen::class, 'id_examen');
    }
}
?>
