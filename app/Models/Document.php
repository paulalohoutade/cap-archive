<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle Document
 * 
 * Représente un fichier archivé dans ArchiveXA.
 * 
 * @property int    $id
 * @property string $nom_fichier      Nom original du fichier
 * @property string $chemin_stockage  Chemin relatif dans le storage
 * @property string $source           Origine du document (upload-manuel, pipeline, etc.)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'nom_fichier',
        'chemin_stockage',
        'source',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Retourne l'URL publique pour accéder au fichier.
     * Utile si tu veux afficher un aperçu ou un lien direct.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->chemin_stockage);
    }

    /**
     * Retourne l'extension du fichier.
     */
    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->nom_fichier, PATHINFO_EXTENSION));
    }

    /**
     * Ajouter url et extension aux données JSON automatiquement.
     */
    protected $appends = ['url', 'extension'];
}
