<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'main_image',
    ];
    
    /**
     * Les attributs calculés qui doivent être ajoutés aux tableaux du modèle.
     *
     * @var array
     */
    protected $appends = ['stock_quantity', 'stock_status'];
    
    /**
     * Relation avec le stock du produit.
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
    
    /**
     * Obtenir la quantité en stock.
     *
     * @return int
     */
    public function getStockQuantityAttribute()
    {
        return $this->stock ? $this->stock->quantity : 0;
    }
    
    /**
     * Obtenir le statut du stock.
     *
     * @return string
     */
    public function getStockStatusAttribute()
    {
        if (!$this->stock) {
            return Stock::STATUS_OUT_OF_STOCK;
        }
        
        if ($this->stock->isOutOfStock()) {
            return Stock::STATUS_OUT_OF_STOCK;
        }
        
        if ($this->stock->isLow()) {
            return Stock::STATUS_LOW_STOCK;
        }
        
        return Stock::STATUS_IN_STOCK;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Relation avec les photos du produit.
     */
    public function photos()
    {
        return $this->hasMany(ProductPhoto::class)->orderBy('order');
    }

    /**
     * Relation avec la photo principale du produit.
     */
    public function primaryPhoto()
    {
        return $this->hasOne(ProductPhoto::class)->where('is_primary', true);
    }

    /**
     * Obtenir l'URL de l'image principale.
     *
     * @return string
     */
    public function getMainImageUrlAttribute()
    {
        // Si une photo principale est définie, on l'utilise
        if ($this->primaryPhoto) {
            return $this->primaryPhoto->url;
        }
        
        // Sinon, on essaie d'utiliser l'ancien système main_image
        if ($this->main_image) {
            if (strpos($this->main_image, 'storage/') === 0) {
                return asset($this->main_image);
            }
            return asset('storage/' . ltrim($this->main_image, '/'));
        }
        
        // Si aucune image n'est disponible, on retourne une image par défaut
        return asset('images/placeholder.png');
    }
    
    /**
     * Définir la photo principale du produit.
     *
     * @param  \App\Models\ProductPhoto  $photo
     * @return void
     */
    public function setPrimaryPhoto(ProductPhoto $photo)
    {
        // D'abord, on enlève le statut de photo principale à toutes les autres photos
        $this->photos()->update(['is_primary' => false]);
        
        // Ensuite, on définit la nouvelle photo comme principale
        $photo->is_primary = true;
        $photo->save();
        
        // Mise à jour de l'image principale dans la table products
        $this->main_image = $photo->path;
        $this->save();
    }
}