# Gestion des images dans l'application e-commerce

## Problème rencontré
Les images des produits ne s'affichaient pas dans le catalogue malgré :
- La configuration correcte du stockage Laravel
- L'existence du lien symbolique `public/storage`
- La présence des fichiers images dans `storage/app/public/products/`

## Cause du problème
Le chemin des images dans la base de données ne correspondait pas à la structure des dossiers. Les chemins enregistrés ne contenaient pas le préfixe `products/` nécessaire pour localiser correctement les fichiers dans le stockage.

## Solution mise en place

1. **Mise à jour des chemins dans la base de données**
   - Les chemins des images ont été mis à jour pour inclure le dossier `products/`
   - Exemple : `nom-image.jpg` → `products/nom-image.jpg`

2. **Création d'un accesseur dans le modèle Product**
   ```php
   public function getMainImageUrlAttribute()
   {
       if (!$this->main_image) {
           return asset('images/placeholder.png');
       }

       if (strpos($this->main_image, 'storage/') === 0) {
           return asset($this->main_image);
       }

       return asset('storage/' . ltrim($this->main_image, '/'));
   }
   ```

3. **Utilisation de l'accesseur dans les vues**
   ```php
   <img src="{{ $product->main_image_url }}" class="card-img-top product-img" alt="{{ $product->name }}">
   ```

## Bonnes pratiques pour l'ajout de nouveaux produits

1. **Stockage des images**
   - Téléverser les images dans le dossier `storage/app/public/products/`
   - Utiliser la méthode `store()` de Laravel pour gérer le stockage :
     ```php
     $path = $request->file('main_image')->store('products', 'public');
     ```

2. **Mise à jour de la base de données**
   - Stocker uniquement le chemin relatif (ex: `products/nom-image.jpg`)
   - Ne pas inclure `storage/` dans le chemin stocké en base de données

3. **Images par défaut**
   - Une image par défaut est utilisée si aucune image n'est définie
   - Le fichier se trouve dans `public/images/placeholder.png`

## Dépannage

Si les images ne s'affichent pas :
1. Vérifier que le lien symbolique `public/storage` pointe vers `storage/app/public`
2. Vérifier les permissions des dossiers (lecture/écriture)
3. Vérifier que les chemins dans la base de données commencent par `products/`
4. Vérifier que les fichiers existent bien dans `storage/app/public/products/`
