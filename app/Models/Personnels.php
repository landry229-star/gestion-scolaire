<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Pour la connexion
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Personnels extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'personnels'; // Harmonisé avec la migration
    protected $primaryKey = 'id_personnel';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'sexe',
        'post', // Sera 'Chef Scolarité' par défaut
        'photo',
        'password', // Ajoute ceci si le personnel se connecte directement
    ];

    // Relation pour voir les inscriptions traitées
    public function inscriptionsValidees()
    {
        return $this->hasMany(Inscription::class, 'id_personnel');
    }


    /**
     * Relation : Un membre du personnel appartient à un département.
     */
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'id_departement');
    }

    /**
     * Relation : Lien avec le compte utilisateur pour la connexion (Login).
     */
    public function utilisateur()
    {
        return $this->hasOne(Utilisateur::class, 'id_personnel', 'id_personnel');
    }
}
?>
