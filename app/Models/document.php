<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentFactory> */
    use HasFactory;
    protected $table = 'documents';
    protected $primaryKey = 'id_document';
    public $timestamps = true; // Souvent utile pour savoir quand le doc a été uploadé

    protected $fillable = [
        'id_dossier',
        'nom_document',   // ex: "Acte de Naissance"
        'chemin_fichier', // ex: "documents/etudiants/nom_fichier.pdf"
        'type_mime',      // ex: "application/pdf"
    ];

    /**
     * Relation : Un document appartient à un dossier d'inscription.
     */
    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'id_dossier', 'id_dossier');
    }
}
