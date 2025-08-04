<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPhoto extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'path',
        'original_name',
        'mime_type',
        'size',
        'order',
        'is_primary',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'size' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Relation avec le modèle Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtenir l'URL complète de la photo.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Obtenir le chemin de stockage du fichier.
     *
     * @return string
     */
    public function getStoragePath(): string
    {
        return 'public/' . $this->path;
    }
}
