<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'main_image',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the main image URL.
     *
     * @return string
     */
    public function getMainImageUrlAttribute()
    {
        if (!$this->main_image) {
            \Log::info('Aucune image principale pour le produit ID: ' . $this->id);
            return asset('images/placeholder.png');
        }

        // Si le chemin commence déjà par 'storage/', on le retourne tel quel
        if (strpos($this->main_image, 'storage/') === 0) {
            $url = asset($this->main_image);
            \Log::info('URL de l\'image (déjà avec storage/): ' . $url);
            return $url;
        }

        // Sinon, on ajoute 'storage/' au début du chemin
        $url = asset('storage/' . ltrim($this->main_image, '/'));
        \Log::info('URL de l\'image (avec storage/ ajouté): ' . $url);
        return $url;
    }
}