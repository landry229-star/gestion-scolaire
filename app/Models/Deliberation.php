<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deliberation extends Model
{
    protected $table = 'deliberations';

    protected $primaryKey =
        'id_deliberation';

    protected $fillable = [

        'id_etudiant',

        'moyenne_generale',

        'rang',

        'credits_valides',

        'decision'
    ];

    public function etudiant()
    {
        return $this->belongsTo(
            Etudiants::class,
            'id_etudiant',
            'id_etudiant'
        );
    }
}
