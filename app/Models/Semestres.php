<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semestres extends Model
{
    /** @use HasFactory<\Database\Factories\SemestresFactory> */
    use HasFactory;

    protected $table = 'semestres';
    protected $primaryKey = 'id_semestre';
    public $timestamps = true;

    protected $fillable = ['code_semestre','description'];

    // Un semestre contient plusieurs matières
    public function Matieres()
    {
        return $this->hasMany(Matieres::class, 'id_semestre', 'id_semestre');
    }
}
?>
