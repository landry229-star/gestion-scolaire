<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id_utilisateur';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'type_utilisateur',
        'id_personnel',
        'id_etudiant',
        'id_enseignant',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // --- RELATIONS ---

    public function etudiant()
    {
        return $this->belongsTo(Etudiants::class, 'id_etudiant', 'id_etudiant');
    }
    

    public function personnel()
    {
        return $this->belongsTo(Personnels::class, 'id_personnel');
    }

    // --- MÉTHODES DE VÉRIFICATION ---

    public function isEtudiant()
    {
        return $this->type_utilisateur === 'etudiant';
    }

    public function isPersonnel()
    {
        return $this->type_utilisateur === 'personnel';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
?>
