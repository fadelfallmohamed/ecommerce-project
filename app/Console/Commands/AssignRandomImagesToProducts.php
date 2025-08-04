<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductPhoto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AssignRandomImagesToProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:assign-images {--force : Forcer la réattribution des images même si le produit en a déjà une}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assigne des images aux produits depuis le dossier storage/products en fonction de mots-clés dans les noms de produits';

    /**
     * Chemins des dossiers d'images.
     *
     * @var array
     */
    protected $imagePaths = [
        'main' => 'products',
        'additional' => 'products/additional'
    ];

    /**
     * Mots-clés pour la correspondance des images.
     *
     * @var array
     */
    protected $imageKeywords = [
        'samsung' => ['samsung', 'galaxy', 's21', 's22', 's23', 'note', 'tab'],
        'iphone' => ['iphone', 'apple', 'ios'],
        'tv' => ['tv', 'téléviseur', 'écran', 'oled', 'qled', 'smart tv'],
        'laptop' => ['laptop', 'ordinateur', 'pc portable', 'notebook', 'macbook', 'ultrabook', 'chromebook'],
        'camera' => ['appareil photo', 'camera', 'appareil photo numérique', 'reflex', 'hybride', 'bridge', 'compact'],
        'headphone' => ['écouteur', 'casque', 'headphone', 'earbuds', 'airpods', 'sans fil', 'bluetooth'],
        'smartwatch' => ['montre', 'watch', 'smartwatch', 'apple watch', 'galaxy watch', 'montre connectée'],
        'gaming' => ['console', 'ps5', 'playstation', 'xbox', 'nintendo', 'manette', 'jeu', 'gaming', 'gamer'],
        'accessory' => ['accessoire', 'câble', 'chargeur', 'étui', 'coque', 'support', 'housse', 'protection'],
        'tablet' => ['tablette', 'ipad', 'galaxy tab', 'mediapad', 'tab s'],
        'printer' => ['imprimante', 'scanner', 'multifonction', 'jet d\'encre', 'laser'],
        'monitor' => ['écran', 'moniteur', 'curved', '4k', 'ultrawide', 'gaming'],
        'speaker' => ['enceinte', 'haut-parleur', 'bluetooth', 'son', 'audio', 'soundbar']
    ];

    /**
     * Exécute la commande de console.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Début de l\'attribution des images aux produits...');
        
        // Vérifier que les dossiers existent
        foreach ($this->imagePaths as $type => $path) {
            $fullPath = storage_path('app/public/' . $path);
            if (!File::exists($fullPath)) {
                $this->error("Le dossier $fullPath n'existe pas.");
                return 1;
            }
        }

        // Récupérer les produits
        $query = Product::query();
        
        // Si l'option --force n'est pas activée, ne prendre que les produits sans image
        if (!$this->option('force')) {
            $query->whereNull('main_image');
        }
        
        $products = $query->get();
        
        if ($products->isEmpty()) {
            $this->info('Aucun produit à mettre à jour.');
            if (!$this->option('force')) {
                $this->info('Utilisez l\'option --force pour forcer la mise à jour de tous les produits.');
            }
            return 0;
        }

        // Récupérer les images disponibles
        $images = [];
        foreach ($this->imagePaths as $type => $path) {
            $files = File::files(storage_path('app/public/' . $path));
            foreach ($files as $file) {
                $fileName = $file->getFilename();
                $images[$type][] = [
                    'path' => $path . '/' . $fileName,
                    'name' => $fileName,
                    'type' => $type
                ];
            }
        }

        if (empty($images)) {
            $this->error('Aucune image trouvée dans les dossiers spécifiés.');
            return 1;
        }

        $this->info('Traitement de ' . $products->count() . ' produits...');
        $bar = $this->output->createProgressBar($products->count());
        $bar->start();

        $updated = 0;
        $skipped = 0;

        DB::beginTransaction();
        
        try {
            foreach ($products as $product) {
                $productName = strtolower($product->name);
                $matchedImages = [];
                $mainImage = null;
                $additionalImages = [];

                // Trouver les images correspondantes en fonction du nom du produit
                foreach ($this->imageKeywords as $imageType => $keywords) {
                    foreach ($keywords as $keyword) {
                        if (str_contains($productName, $keyword)) {
                            // Ajouter les images principales correspondantes
                            if (isset($images['main'])) {
                                $matchedMainImages = array_filter($images['main'], function($img) use ($imageType) {
                                    return str_contains(strtolower($img['name']), $imageType);
                                });
                                
                                if (!empty($matchedMainImages)) {
                                    $mainImage = $matchedMainImages[array_rand($matchedMainImages)];
                                }
                            }
                            
                            // Ajouter des images supplémentaires correspondantes (max 3)
                            if (isset($images['additional'])) {
                                $matchedAddImages = array_filter($images['additional'], function($img) use ($imageType) {
                                    return str_contains(strtolower($img['name']), $imageType);
                                });
                                
                                // Prendre jusqu'à 3 images supplémentaires
                                if (!empty($matchedAddImages)) {
                                    shuffle($matchedAddImages);
                                    $additionalImages = array_slice($matchedAddImages, 0, 3);
                                }
                            }
                            
                            break 2; // Sortir des deux boucles dès qu'une correspondance est trouvée
                        }
                    }
                }

                // Si aucune image principale correspondante trouvée, en prendre une aléatoire
                if (!$mainImage && !empty($images['main'])) {
                    $mainImage = $images['main'][array_rand($images['main'])];
                }

                // Mettre à jour le produit avec l'image principale
                if ($mainImage) {
                    $product->update(['main_image' => $mainImage['path']]);
                    $updated++;
                    
                    // Ajouter des images supplémentaires si disponibles
                    if (!empty($additionalImages)) {
                        $order = 1;
                        foreach ($additionalImages as $img) {
                            // Vérifier si la photo existe déjà pour ce produit
                            $exists = $product->photos()->where('path', $img['path'])->exists();
                            
                            if (!$exists) {
                                $product->photos()->create([
                                    'path' => $img['path'],
                                    'order' => $order++,
                                    'is_primary' => false
                                ]);
                            }
                        }
                    }
                } else {
                    $skipped++;
                }
                
                $bar->advance();
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Une erreur est survenue : ' . $e->getMessage());
            return 1;
        }
        
        $bar->finish();
        
        $this->newLine(2);
        $this->info("Terminé ! $updated produits mis à jour, $skipped produits ignorés.");
        
        if ($skipped > 0) {
            $this->info('Les produits ignorés ont peut-être déjà une image et l\'option --force n\'a pas été utilisée.');
        }
        
        return 0;
    }
}
