<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossiers extends Model
{
    /** @use HasFactory<\Database\Factories\DossiersFactory> */
    use HasFactory;

protected $table = 'dossiers';
    protected $primaryKey = 'id_dossier';

    // On active les timestamps pour suivre la date de dépôt et de modification
    public $timestamps = true;

    protected $fillable = [
        'code_dossier',      // ex: DOS-2026-001
        'date_depot',
        'type_dossier',      // ex: Inscription, Réinscription, Transfert
    ];

    /**
     * Relation : Un dossier appartient à une inscription.
     * (C'est le lien inverse de Inscription -> Dossier)
     */
    public function inscription()
    {
        return $this->hasOne(Inscription::class, 'id_dossier', 'id_dossier');
    }

    /**
     * Relation : Un dossier contient plusieurs documents (pièces jointes).
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'id_dossier', 'id_dossier');
    }
}
?>
