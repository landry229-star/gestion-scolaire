<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateurs extends Model
{
    /** @use HasFactory<\Database\Factories\UtilisateursFactory> */
    use HasFactory;
    // Dans Utilisateurs.php
protected $table      = 'utilisateurs';
protected $primaryKey = 'id_utilisateur';
    protected $fillable = [
        'nom',
        'email',
        'password',
        'type_utilisateur', // ex: 'personnel', 'etudiant', 'enseignant'
        'id_personnel',     // Clé étrangère vers la table personnel
        'id_etudiant',      // Clé étrangère vers la table etudiant
        'id_enseignant',    // Clé étrangère vers la table enseignant
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relation : L'utilisateur peut être un membre du personnel.
     */
    public function personnel()
    {
        return $this->belongsTo(Personnels::class, 'id_personnel');
    }

    /**
     * Relation : L'utilisateur peut être un étudiant.
     */
    public function etudiant()
    {
        return $this->belongsTo(Etudiants::class, 'id_etudiant');
    }

    /**
     * Relation : L'utilisateur peut être un enseignant.
     */
    public function enseignant()
    {
        return $this->belongsTo(Enseignants::class, 'id_enseignant');
    }


    /**
     * Méthode pratique : Vérifier si l'utilisateur est un admin ou scolarité
     */
    public function isPersonnel()
    {
        return $this->type_utilisateur === 'personnel';
    }

}
?>
