<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPhotoPolicy
{
    /**
     * Déterminer si l'utilisateur peut voir n'importe quelle photo du produit.
     */
    public function viewAny(User $user, Product $product): bool
    {
        // Seuls les administrateurs peuvent voir les photos des produits
        return $user->isAdmin();
    }

    /**
     * Déterminer si l'utilisateur peut voir une photo spécifique.
     */
    public function view(User $user, ProductPhoto $photo): bool
    {
        // Un utilisateur peut voir la photo s'il est admin ou propriétaire du produit
        return $user->isAdmin() || $photo->product->user_id === $user->id;
    }

    /**
     * Déterminer si l'utilisateur peut créer des photos pour un produit.
     */
    public function create(User $user, Product $product): bool
    {
        // Seuls les administrateurs peuvent ajouter des photos
        return $user->isAdmin();
    }

    /**
     * Déterminer si l'utilisateur peut mettre à jour une photo.
     */
    public function update(User $user, ProductPhoto $photo): bool
    {
        // Seuls les administrateurs peuvent modifier les photos
        return $user->isAdmin();
    }

    /**
     * Déterminer si l'utilisateur peut supprimer une photo.
     */
    public function delete(User $user, ProductPhoto $photo): bool
    {
        // Empêcher la suppression de la dernière photo d'un produit
        if ($photo->product->photos()->count() <= 1) {
            return false;
        }
        
        // Seuls les administrateurs peuvent supprimer des photos
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProductPhoto $productPhoto): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProductPhoto $productPhoto): bool
    {
        //
    }
}
