<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeInscription extends Model
{
    protected $table    = 'periode_inscriptions';
    protected $fillable = ['date_debut', 'date_fin', 'annee', 'active'];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'active'     => 'boolean',
    ];

    /** Vérifie si les inscriptions sont ouvertes */
    public static function isOuverte(): bool
    {
        $periode = self::where('active', true)->first();
        return $periode && now()->between($periode->date_debut, $periode->date_fin);
    }
}
?>
