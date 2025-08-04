<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductPhotoController extends Controller
{
    /**
     * Afficher la liste des photos d'un produit.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $this->authorize('viewAny', [ProductPhoto::class, $product]);
        
        $photos = $product->photos()->orderBy('order')->get();
        
        return view('admin.products.photos.index', compact('product', 'photos'));
    }

    /**
     * Afficher le formulaire d'ajout de photo.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
        $this->authorize('create', [ProductPhoto::class, $product]);
        
        return view('admin.products.photos.create', compact('product'));
    }

    /**
     * Enregistrer une nouvelle photo de produit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        \Log::info('=== DÉBUT MÉTHODE STORE ===', ['product_id' => $product->id]);
        \Log::info('Données de la requête:', $request->all());
        \Log::info('Fichiers dans la requête:', $request->allFiles());
        
        // Vérification de l'authentification et des autorisations
        \Log::info('Vérification de l\'utilisateur connecté:', [
            'user_id' => auth()->id(),
            'is_admin' => auth()->user() ? auth()->user()->isAdmin() : 'non connecté'
        ]);
        
        try {
            $this->authorize('create', [ProductPhoto::class, $product]);
            \Log::info('Autorisation réussie');
            
            // Vérification des fichiers reçus
            if (!$request->hasFile('images')) {
                \Log::error('Aucun fichier reçu dans la requête');
                return back()->with('error', 'Aucun fichier reçu. Veuillez sélectionner au moins une image.');
            }
            
            $files = $request->file('images');
            \Log::info('Fichiers reçus', [
                'count' => is_countable($files) ? count($files) : 'non comptable',
                'type' => gettype($files),
                'is_array' => is_array($files)
            ]);
            
            // Vérification des permissions du dossier de stockage
            $storagePath = storage_path('app/public/products/' . $product->id);
            if (!file_exists($storagePath)) {
                \Storage::makeDirectory('public/products/' . $product->id);
                \Log::info('Dossier de stockage créé', ['path' => $storagePath]);
            }
            
            \Log::info('Permissions du dossier de stockage:', [
                'path' => $storagePath,
                'exists' => file_exists($storagePath),
                'writable' => is_writable($storagePath),
                'permissions' => substr(sprintf('%o', fileperms($storagePath)), -4)
            ]);
            
            // Détails des fichiers
            $filesDetails = [];
            if (is_array($files) || $files instanceof \Traversable) {
                foreach ($files as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {
                        $filesDetails[] = [
                            'name' => $file->getClientOriginalName(),
                            'size' => $file->getSize(),
                            'mime' => $file->getMimeType(),
                            'error' => $file->getError(),
                            'valid' => $file->isValid(),
                            'temp_path' => $file->getPathname(),
                            'extension' => $file->getClientOriginalExtension(),
                        ];
                    }
                }
            }
            \Log::info('Détails des fichiers:', $filesDetails);
        
            // Validation des fichiers
            $validated = $request->validate([
                'images' => 'required|array|min:1',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
                'is_primary' => 'sometimes|boolean',
            ]);
            
            \Log::info('Validation réussie', [
                'nombre_images' => is_countable($files) ? count($files) : 'non comptable',
                'validated_data' => $validated
            ]);
            
            $uploadedPhotos = [];
            // Traitement de chaque image
            foreach ($request->file('images') as $key => $image) {
                $originalName = $image->getClientOriginalName();
                \Log::info("Traitement de l'image $key: $originalName");
                
                try {
                    // Génération d'un nom de fichier unique
                    $fileName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs("products/{$product->id}", $fileName, 'public');
                    
                    if (!$path) {
                        throw new \Exception("Échec du stockage du fichier");
                    }
                    
                    \Log::info('Image stockée avec succès', [
                        'original_name' => $originalName,
                        'stored_path' => $path,
                        'full_path' => storage_path('app/public/' . $path)
                    ]);
                    
                    // Préparation des données pour la base de données
                    $photoData = [
                        'product_id' => $product->id,
                        'path' => $path,
                        'original_name' => $originalName,
                        'mime_type' => $image->getClientMimeType(),
                        'size' => $image->getSize(),
                        'order' => $product->photos()->count(),
                        'is_primary' => $request->has('is_primary') && $request->boolean('is_primary'),
                    ];
                    
                    \Log::info('Tentative de création de la photo en base de données', $photoData);
                    
                    // Création de l'entrée en base de données
                    try {
                        $photo = new ProductPhoto($photoData);
                        $saved = $photo->save();
                        
                        if (!$saved) {
                            \Log::error('Échec de l\'enregistrement de la photo en base de données', [
                                'photo_data' => $photoData,
                                'errors' => $photo->getErrors()
                            ]);
                            continue;
                        }
                        
                        \Log::info('Photo créée avec succès', [
                            'photo_id' => $photo->id,
                            'product_id' => $photo->product_id,
                            'path' => $photo->path,
                            'is_primary' => $photo->is_primary
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Exception lors de la création de la photo', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'photo_data' => $photoData
                        ]);
                        throw $e;
                    }
                    
                    $uploadedPhotos[] = $photo;
                    
                    // Mise à jour de la photo principale si nécessaire
                    if ($photo->is_primary) {
                        $product->setPrimaryPhoto($photo);
                        \Log::info('Photo définie comme principale', ['photo_id' => $photo->id]);
                    }
                } catch (\Exception $e) {
                    \Log::error("Erreur lors du traitement de l'image $originalName", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            }
            
            // Redirection avec succès
            $message = count($uploadedPhotos) . ' photo(s) téléversée(s) avec succès';
            \Log::info('Opération terminée avec succès', [
                'photos_uploaded' => count($uploadedPhotos),
                'product_id' => $product->id
            ]);
            
            return redirect()
                ->route('admin.products.photos.index', $product)
                ->with('success', $message);
            
        } catch (\Exception $e) {
            // En cas d'erreur, nettoyage des fichiers déjà uploadés
            \Log::error('ERREUR lors du téléversement des photos', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'uploaded_photos_count' => count($uploadedPhotos)
            ]);
            
            foreach ($uploadedPhotos as $photo) {
                try {
                    if (isset($photo->path) && Storage::disk('public')->exists($photo->path)) {
                        Storage::disk('public')->delete($photo->path);
                        \Log::info('Fichier supprimé après erreur', ['path' => $photo->path]);
                    }
                    
                    if (isset($photo->id)) {
                        $photo->delete();
                        \Log::info('Entrée supprimée de la base de données', ['photo_id' => $photo->id]);
                    }
                } catch (\Exception $deleteError) {
                    \Log::error('Erreur lors du nettoyage après échec', [
                        'error' => $deleteError->getMessage(),
                        'photo_id' => $photo->id ?? 'inconnu'
                    ]);
                }
            }
            
            $errorMessage = 'Une erreur est survenue lors du téléversement des photos: ' . $e->getMessage();
            \Log::error($errorMessage);
            
            return back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Afficher une photo spécifique.
     *
     * @param  \App\Models\ProductPhoto  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(ProductPhoto $photo)
    {
        $this->authorize('view', $photo);
        
        return response()->file(storage_path('app/public/' . $photo->path));
    }

    /**
     * Afficher le formulaire de réorganisation des photos.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        
        $photos = $product->photos()->orderBy('order')->get();
        
        return view('admin.products.photos.edit', compact('product', 'photos'));
    }
    
    /**
     * Mettre à jour l'ordre des photos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updateOrder(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        
        $request->validate([
            'photos' => 'required|array',
            'photos.*.id' => 'required|exists:product_photos,id,product_id,' . $product->id,
            'photos.*.order' => 'required|integer|min:1',
            'photos.*.is_primary' => 'sometimes|boolean'
        ]);
        
        try {
            \DB::beginTransaction();
            
            foreach ($request->photos as $photoData) {
                $photo = ProductPhoto::findOrFail($photoData['id']);
                
                $photo->update([
                    'order' => $photoData['order'],
                    'is_primary' => $photoData['is_primary'] ?? false
                ]);
                
                // Si c'est la photo principale, on met à jour le produit
                if ($photo->is_primary) {
                    $product->setPrimaryPhoto($photo);
                }
            }
            
            \DB::commit();
            
            return redirect()
                ->route('admin.products.photos.index', $product)
                ->with('success', 'Ordre des photos mis à jour avec succès');
                
        } catch (\Exception $e) {
            \DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'ordre des photos: ' . $e->getMessage());
        }
    }
    
    /**
     * Définir une photo comme principale.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductPhoto  $photo
     * @return \Illuminate\Http\Response
     */
    public function setPrimary(Product $product, ProductPhoto $photo)
    {
        $this->authorize('update', $product);
        
        if ($photo->product_id !== $product->id) {
            abort(404);
        }
        
        try {
            $product->setPrimaryPhoto($photo);
            
            return back()->with('success', 'Photo principale mise à jour avec succès');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour de la photo principale');
        }
    }
    
    /**
     * Supprimer une photo de produit.
     *
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductPhoto  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, ProductPhoto $photo)
    {
        $this->authorize('delete', $photo);
        
        if ($photo->product_id !== $product->id) {
            abort(404);
        }
        
        // Vérifier qu'il reste au moins une photo
        if ($product->photos()->count() <= 1) {
            return back()->with('error', 'Impossible de supprimer la dernière photo du produit');
        }
        
        try {
            // Supprimer le fichier physique
            Storage::disk('public')->delete($photo->path);
            
            // Supprimer l'enregistrement en base de données
            $photo->delete();
            
            // Si c'était la photo principale, on en définit une nouvelle
            if ($product->main_image === $photo->path) {
                $newPrimaryPhoto = $product->photos()->first();
                if ($newPrimaryPhoto) {
                    $product->setPrimaryPhoto($newPrimaryPhoto);
                }
            }
            
            return back()->with('success', 'Photo supprimée avec succès');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la photo');
        }
    }

    /**
     * Mettre à jour une photo de produit.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductPhoto  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, ProductPhoto $photo)
    {
        $this->authorize('update', $photo);
        
        if ($photo->product_id !== $product->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'is_primary' => 'sometimes|boolean'
        ]);
        
        try {
            $photo->update($validated);
            
            if ($photo->is_primary) {
                $product->setPrimaryPhoto($photo);
            }
            
            return back()->with('success', 'Photo mise à jour avec succès');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour de la photo');
        }
    }
}
