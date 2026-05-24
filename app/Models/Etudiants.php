<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Filieres;
use App\Models\Inscription;
use App\Models\Notes;
use App\Models\Document;
use App\Models\Examen;
use App\Models\Deliberation;
use App\Models\EtudiantExamen;

class Etudiants extends Model
{
    use HasFactory;

    protected $table = 'etudiants';

    protected $primaryKey = 'id_etudiant';

    public $timestamps = true;

    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'sexe',
        'lieu_naissance',
        'date_naissance',
        'nationalite',
        'email',
        'telephone',
        'photo',
        'id_filiere',
        'statut_compte',
        'niveau',
        'adresse',
        'document_cip',
        'diplome_bac',
        'groupe_sanguin', 
    'num_table_bac', 'serie_bac', 'annee_bac',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getNomCompletAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function utilisateur()
    {
        return $this->hasOne(User::class, 'id_etudiant', 'id_etudiant');
    }

    public function filiere()
    {
        return $this->belongsTo(Filieres::class, 'id_filiere', 'id_filiere');
    }

public function inscription()
    {
        // On lie Inscription à Etudiant via 'id_etudiant' (clé étrangère et clé locale)
        // On trie par ID d'inscription décroissant pour toujours obtenir la plus récente
        return $this->hasOne(Inscription::class, 'id_etudiant', 'id_etudiant')
                    ->orderBy('id_inscription', 'desc');
    }

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'id_etudiant', 'id_etudiant');
    }

   // public function inscription()
    //{
      //  return $this->hasOne(
       //     Inscription::class,
         //   'id_etudiant',
           // 'id_etudiant'
      //  )->latestOfMany();
   // }

    public function notes()
    {
        return $this->hasMany(Notes::class, 'id_etudiant', 'id_etudiant');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'id_etudiant', 'id_etudiant');
    }

    public function deliberation()
    {
        return $this->hasOne(
            Deliberation::class,
            'id_etudiant',
            'id_etudiant'
        );
    }

    public function examens()
    {
        return $this->belongsToMany(
            Examen::class,
            'etudiant_examen',
            'id_etudiant',
            'id_examen'
        );
    }

    public function resultatsExamens()
    {
        return $this->hasMany(
            EtudiantExamen::class,
            'id_etudiant',
            'id_etudiant'
        );
    }
}
