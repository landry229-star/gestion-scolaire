<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    /** @use HasFactory<\Database\Factories\DepartementFactory> */
    use HasFactory;

    protected $table      = 'departements';
    protected $primaryKey = 'id_departement';
    protected $fillable   = ['nom_departement', 'id_personnel'];

    public function filieres()
    {
        return $this->hasMany(Filieres::class, 'id_filiere', 'id_filiere');
    }
    public function filiere()
{
    // Un département a plusieurs filières
    return $this->hasMany(Filieres::class, 'id_departement', 'id_departement');
}

    public function enseignants()
    {
        return $this->hasMany(Enseignants::class, 'id_enseignant', 'id_enseignant');
    }
}
?>
