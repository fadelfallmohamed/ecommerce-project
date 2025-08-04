<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Obtenez les produits associés à cette marque.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Obtenez le chemin du logo de la marque.
     *
     * @return string
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        
        return asset('images/default-brand.png');
    }

    /**
     * Obtenez l'URL du site web de la marque avec le protocole si nécessaire.
     *
     * @return string
     */
    public function getWebsiteUrlAttribute()
    {
        if (!$this->website) {
            return null;
        }

        if (!preg_match("~^https?://~i", $this->website)) {
            return 'https://' . $this->website;
        }

        return $this->website;
    }

    /**
     * Scope pour ne récupérer que les marques actives.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
