<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paiements extends Model
{
    /** @use HasFactory<\Database\Factories\PaiementsFactory> */
    use HasFactory;

     protected $table = 'paiements';
    protected $primaryKey = 'id_paiement';
    public $timestamps = true;

    protected $fillable = [
        'id_inscription',
        'type_paiement', // Nom de la banque
        'statut_paiement',
        'montant',
        'recu_chemin', // Chemin vers le PDF du reçu
    ];

    /**
     * Relation : Un paiement appartient à une inscription.
     */
    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'id_inscription', 'id_inscription');
    }
}
?>
