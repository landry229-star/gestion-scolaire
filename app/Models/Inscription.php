<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $table = 'inscriptions';
    protected $primaryKey = 'id_inscription';

    public $timestamps = true;

    protected $fillable = [
        'id_etudiant',
    'id_filiere',
    'telephone',
    'adresse',
    'groupe_sanguin',
    'annee_academique',
    'num_table_bac',
    'serie_bac',
    'annee_bac',
    'entite',
    'annee_etude',
    'regime',
    'statut_boursier',
    'montant_frais',
    'restant_du',
    'statut_dossier',
    'motif_rejet'
    ];

    /**
     |--------------------------------------------------------------------------
     | RELATIONS ÉLOQUENT
     |--------------------------------------------------------------------------
     */

    public function etudiant()
    {
        // Assure-toi que le nom de ta classe correspond bien (Etudiant ou Etudiants)
        return $this->belongsTo(Etudiants::class, 'id_etudiant', 'id_etudiant');
    }

    public function filiere()
    {
        // Assure-toi que le nom de ta classe correspond bien (Filiere ou Filieres)
        return $this->belongsTo(Filieres::class, 'id_filiere', 'id_filiere');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'id_inscription', 'id_inscription');
    }
}
