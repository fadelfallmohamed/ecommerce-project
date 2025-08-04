<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'quantity',
        'alert_quantity',
        'purchase_price',
        'selling_price',
        'sku',
        'barcode',
        'location',
        'notes',
        'status',
        'last_updated_by',
        'last_restocked_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'last_restocked_at' => 'datetime',
    ];

    /**
     * Les états de stock possibles.
     */
    public const STATUS_IN_STOCK = 'in_stock';
    public const STATUS_LOW_STOCK = 'low_stock';
    public const STATUS_OUT_OF_STOCK = 'out_of_stock';

    /**
     * Relation avec le produit.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relation avec l'utilisateur qui a mis à jour le stock.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    /**
     * Vérifie si le stock est bas.
     */
    public function isLow(): bool
    {
        return $this->quantity <= $this->alert_quantity && $this->quantity > 0;
    }

    /**
     * Vérifie si le stock est épuisé.
     */
    public function isOutOfStock(): bool
    {
        return $this->quantity <= 0;
    }

    /**
     * Met à jour la quantité en stock et le statut.
     */
    public function updateQuantity(int $quantity, ?string $notes = null): void
    {
        $this->quantity = $quantity;
        $this->updateStatus();
        $this->last_updated_by = Auth::id();
        $this->last_restocked_at = now();
        
        if ($notes) {
            $this->notes = $notes;
        }
        
        $this->save();
    }

    /**
     * Ajoute de la quantité au stock.
     */
    public function addStock(int $quantity, ?string $notes = null): void
    {
        $this->updateQuantity($this->quantity + $quantity, $notes);
    }

    /**
     * Retire de la quantité du stock.
     */
    public function removeStock(int $quantity, ?string $notes = null): void
    {
        $this->updateQuantity(max(0, $this->quantity - $quantity), $notes);
    }

    /**
     * Met à jour le statut du stock en fonction de la quantité disponible.
     */
    protected function updateStatus(): void
    {
        if ($this->isOutOfStock()) {
            $this->status = self::STATUS_OUT_OF_STOCK;
        } elseif ($this->isLow()) {
            $this->status = self::STATUS_LOW_STOCK;
        } else {
            $this->status = self::STATUS_IN_STOCK;
        }
    }

    /**
     * Scope pour les produits en stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('status', self::STATUS_IN_STOCK);
    }

    /**
     * Scope pour les produits en rupture de stock.
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('status', self::STATUS_OUT_OF_STOCK);
    }

    /**
     * Scope pour les produits en stock bas.
     */
    public function scopeLowStock($query)
    {
        return $query->where('status', self::STATUS_LOW_STOCK);
    }

    /**
     * Scope pour les produits nécessitant une réapprovisionnement.
     */
    public function scopeNeedsRestocking($query)
    {
        return $query->whereIn('status', [self::STATUS_LOW_STOCK, self::STATUS_OUT_OF_STOCK]);
    }
}
