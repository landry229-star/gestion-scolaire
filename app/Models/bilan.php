<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bilan extends Model
{
    /** @use HasFactory<\Database\Factories\BilanFactory> */
    use HasFactory;
    protected $table = 'bilan';
    protected $primaryKey = 'id_bilan';
    protected $fillable = ['id_etudiant', 'id_semestre', 'moyenne_semestrielle', 'total_credits_valides', 'resultat', 'observation'];

    public function etudiant() {
        return $this->belongsTo(Etudiant::class, 'id_etudiant');
    }
}
?>
