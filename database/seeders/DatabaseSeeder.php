<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création de l'utilisateur admin s'il n'existe pas
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nom' => 'Admin',
                'prenom' => 'Système',
                'name' => 'Admin Système',
                'password' => bcrypt('admin123'),
                'is_admin' => true,
            ]
        );
        
        if ($admin->wasRecentlyCreated) {
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: admin123');
        } else {
            // Mettre à jour l'utilisateur admin existant pour s'assurer qu'il a les bons droits
            $admin->update(['is_admin' => true]);
            $this->command->info('Existing admin user updated with admin privileges.');
        }

        // Création des marques
        $brands = [
            'Apple' => 'Apple Inc.',
            'Samsung' => 'Samsung Electronics',
            'LG' => 'LG Electronics',
            'Sony' => 'Sony Corporation',
            'Microsoft' => 'Microsoft Corporation',
            'HP' => 'HP Inc.',
            'Dell' => 'Dell Technologies',
            'Lenovo' => 'Lenovo Group',
            'Asus' => 'ASUS',
            'Acer' => 'Acer Inc.',
            'Logitech' => 'Logitech International',
            'Razer' => 'Razer Inc.',
            'Bose' => 'Bose Corporation',
            'JBL' => 'JBL',
            'Canon' => 'Canon Inc.',
            'Nikon' => 'Nikon Corporation',
            'GoPro' => 'GoPro, Inc.',
            'Xiaomi' => 'Xiaomi Corporation',
            'Huawei' => 'Huawei Technologies',
            'OnePlus' => 'OnePlus Technology'
        ];

        $brandIds = [];
        foreach ($brands as $name => $description) {
            $brand = Brand::firstOrCreate(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    'description' => $description,
                    'is_active' => true
                ]
            );
            $brandIds[$name] = $brand->id;
        }

        $this->command->info('Brands created successfully!');

        // Création des catégories
        $categories = [
            'Téléviseurs' => 'Téléviseurs et écrans',
            'Ordinateurs portables' => 'Ordinateurs portables et ultrabooks',
            'Ordinateurs de bureau' => 'Ordinateurs de bureau et tout-en-un',
            'Tablettes' => 'Tablettes tactiles',
            'Smartphones' => 'Téléphones intelligents',
            'Montres connectées' => 'Montres connectées et bracelets intelligents',
            'Écouteurs' => 'Écouteurs et casques audio',
            'Haut-parleurs' => 'Haut-parleurs et enceintes',
            'Imprimantes' => 'Imprimantes et scanners',
            'Accessoires' => 'Accessoires informatiques et électroniques',
            'Composants PC' => 'Composants pour ordinateur',
            'Réseau' => 'Réseau et connectivité',
            'Stockage' => 'Disques durs et stockage',
            'Périphériques' => 'Périphériques et accessoires',
            'Jeux vidéo' => 'Jeux vidéo et consoles',
            'Appareils photo' => 'Appareils photo et caméras',
            'Objets connectés' => 'Objets connectés et domotique',
            'Bureautique' => 'Fournitures de bureau',
            'Sécurité' => 'Sécurité et protection',
            'Accessoires téléphoniques' => 'Accessoires pour téléphones mobiles'
        ];

        $categoryIds = [];
        foreach ($categories as $name => $description) {
            $slug = Str::slug($name);
            $category = Category::firstOrCreate(
                ['name' => $name],
                [
                    'slug' => $slug,
                    'description' => $description,
                    'is_active' => true
                ]
            );
            
            // Mettre à jour le slug si la catégorie existait déjà mais n'avait pas de slug
            if (empty($category->slug)) {
                $category->update(['slug' => $slug, 'is_active' => true]);
            }
            $categoryIds[$name] = $category->id;
        }

        $this->command->info('Categories created successfully!');

        // Ajout de produits exemples
        $products = [
            [
                'name' => 'Téléviseur LG OLED 55"',
                'slug' => 'televiseur-lg-oled-55',
                'description' => 'Téléviseur OLED 4K Ultra HD, HDR, Smart TV, 55 pouces.',
                'short_description' => 'Écran OLED 4K HDR avec processeur Alpha 9 Gen 5 AI',
                'price' => 1299.99,
                'sale_price' => 1199.99,
                'quantity' => 10,
                'alert_quantity' => 2,
                'main_image' => 'https://via.placeholder.com/800x600?text=LG+OLED+55',
                'images' => json_encode([
                    'https://via.placeholder.com/800x600?text=LG+OLED+55+1',
                    'https://via.placeholder.com/800x600?text=LG+OLED+55+2',
                    'https://via.placeholder.com/800x600?text=LG+OLED+55+3'
                ]),
                'weight' => 18.5,
                'length' => 123.2,
                'width' => 71.9,
                'height' => 24.9,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'meta_title' => 'Téléviseur LG OLED 55" 4K HDR Smart TV',
                'meta_description' => 'Découvrez le téléviseur LG OLED 55" 4K HDR Smart TV avec une qualité d\'image exceptionnelle et des fonctionnalités intelligentes.',
                'brand_id' => $brandIds['LG'],
                'category_id' => $categoryIds['Téléviseurs'],
                'category_name' => 'Téléviseurs',
                'purchase_price' => 999.99,
                'sku' => 'TV-LG-OLED55C1',
                'barcode' => '8806094683863',
                'location' => 'A12-3-5'
            ],
            [
                'name' => 'Tablette Samsung Galaxy Tab S8',
                'slug' => 'tablette-samsung-galaxy-tab-s8',
                'description' => 'Tablette Android 11 pouces, 128 Go, Wi-Fi, processeur Octa-Core, 8 Go RAM, écran WQXGA+ 120Hz.',
                'short_description' => 'Tablette haut de gamme avec S Pen inclus',
                'price' => 699.99,
                'sale_price' => 649.99,
                'quantity' => 15,
                'alert_quantity' => 3,
                'main_image' => 'https://via.placeholder.com/800x600?text=Samsung+Tab+S8',
                'images' => json_encode([
                    'https://via.placeholder.com/800x600?text=Tab+S8+1',
                    'https://via.placeholder.com/800x600?text=Tab+S8+2',
                    'https://via.placeholder.com/800x600?text=Tab+S8+3'
                ]),
                'weight' => 0.5,
                'length' => 25.3,
                'width' => 16.5,
                'height' => 0.6,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'meta_title' => 'Tablette Samsung Galaxy Tab S8 11" 128 Go',
                'meta_description' => 'La tablette Samsung Galaxy Tab S8 allie performance et polyvalence avec son écran 11" 120Hz et son S Pen inclus.',
                'brand_id' => $brandIds['Samsung'],
                'category_id' => $categoryIds['Tablettes'],
                'category_name' => 'Tablettes',
                'purchase_price' => 499.99,
                'sku' => 'TAB-SAMSUNG-S8-128',
                'barcode' => '8806094683864',
                'location' => 'B7-2-1'
            ],
            [
                'name' => 'Imprimante HP LaserJet Pro M15w',
                'slug' => 'imprimante-hp-laserjet-pro-m15w',
                'description' => 'Imprimante laser monochrome compacte, impression recto, Wi-Fi, impression mobile, jusqu\'à 19 ppm, noir.',
                'short_description' => 'Imprimante laser compacte idéale pour le bureau à domicile',
                'price' => 199.99,
                'sale_price' => 179.99,
                'quantity' => 25,
                'alert_quantity' => 5,
                'main_image' => 'https://via.placeholder.com/800x600?text=HP+LaserJet+Pro',
                'images' => json_encode([
                    'https://via.placeholder.com/800x600?text=HP+LaserJet+1',
                    'https://via.placeholder.com/800x600?text=HP+LaserJet+2',
                    'https://via.placeholder.com/800x600?text=HP+LaserJet+3'
                ]),
                'weight' => 4.0,
                'length' => 35.8,
                'width' => 18.9,
                'height' => 15.0,
                'is_active' => true,
                'is_featured' => false,
                'is_bestseller' => true,
                'meta_title' => 'Imprimante HP LaserJet Pro M15w sans fil',
                'meta_description' => 'Imprimante laser monochrome HP LaserJet Pro M15w compacte et sans fil pour une impression professionnelle.',
                'brand_id' => $brandIds['HP'],
                'category_id' => $categoryIds['Imprimantes'],
                'category_name' => 'Imprimantes',
                'purchase_price' => 129.99,
                'sku' => 'PRN-HP-M15W',
                'barcode' => '190781302823',
                'location' => 'C3-1-8'
            ],
            [
                'name' => 'Souris Logitech MX Master 3',
                'slug' => 'souris-logitech-mx-master-3',
                'description' => 'Souris sans fil avancée avec défilement ultra-rapide, précision de 4000 PPP, rechargeable USB-C, compatible avec Windows, Mac, Linux, Chrome OS.',
                'short_description' => 'Souris sans fil haut de gamme pour professionnels',
                'price' => 99.99,
                'sale_price' => 89.99,
                'quantity' => 50,
                'alert_quantity' => 10,
                'main_image' => 'https://via.placeholder.com/800x600?text=Logitech+MX+Master+3',
                'images' => json_encode([
                    'https://via.placeholder.com/800x600?text=MX+Master+1',
                    'https://via.placeholder.com/800x600?text=MX+Master+2',
                    'https://via.placeholder.com/800x600?text=MX+Master+3'
                ]),
                'weight' => 0.14,
                'length' => 12.4,
                'width' => 8.4,
                'height' => 5.1,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => true,
                'meta_title' => 'Souris sans fil Logitech MX Master 3',
                'meta_description' => 'La souris sans fil Logitech MX Master 3 offre une précision et un confort exceptionnels pour les professionnels.',
                'brand_id' => $brandIds['Logitech'],
                'category_id' => $categoryIds['Périphériques'],
                'category_name' => 'Périphériques',
                'purchase_price' => 59.99,
                'sku' => 'MSE-LOGI-MXMASTER3',
                'barcode' => '097855097543',
                'location' => 'D5-2-3'
            ],
            [
                'name' => 'Appareil photo Canon EOS 250D',
                'slug' => 'appareil-photo-canon-eos-250d',
                'description' => 'Reflex numérique, capteur 24,1 MP, vidéo 4K, écran tactile vari-angle, Wi-Fi et Bluetooth intégrés.',
                'short_description' => 'Reflex numérique compact et léger avec écran tactile vari-angle',
                'price' => 749.99,
                'sale_price' => 699.99,
                'quantity' => 8,
                'alert_quantity' => 2,
                'main_image' => 'https://via.placeholder.com/800x600?text=Canon+EOS+250D',
                'images' => json_encode([
                    'https://via.placeholder.com/800x600?text=Canon+250D+1',
                    'https://via.placeholder.com/800x600?text=Canon+250D+2',
                    'https://via.placeholder.com/800x600?text=Canon+250D+3'
                ]),
                'weight' => 0.45,
                'length' => 12.2,
                'width' => 9.3,
                'height' => 6.9,
                'is_active' => true,
                'is_featured' => true,
                'is_bestseller' => false,
                'meta_title' => 'Appareil photo Reflex Canon EOS 250D 24.1 MP',
                'meta_description' => 'Découvrez le Reflex Canon EOS 250D avec capteur 24.1 MP, vidéo 4K et écran tactile vari-angle pour des photos et vidéos de qualité professionnelle.',
                'brand_id' => $brandIds['Canon'],
                'category_id' => $categoryIds['Appareils photo'],
                'category_name' => 'Appareils photo',
                'purchase_price' => 549.99,
                'sku' => 'CAM-CAN-250D',
                'barcode' => '0013803307187',
                'location' => 'E2-4-7'
            ],
            [
                'name' => 'Enceinte Bluetooth JBL Charge 5',
                'description' => 'Enceinte portable, étanche, autonomie 20h.',
                'price' => 179.99,
                'stock' => 40,
                'main_image' => 'https://via.placeholder.com/300x300?text=Audio',
                'category' => 'Audio',
            ],
            [
                'name' => 'Caméra de surveillance Xiaomi Mi',
                'description' => 'Caméra IP Wi-Fi, vision nocturne, détection de mouvement.',
                'price' => 59.99,
                'stock' => 60,
                'main_image' => 'https://via.placeholder.com/300x300?text=Caméra',
                'category' => 'Caméras de surveillance',
            ],
            [
                'name' => 'Drone DJI Mini 2',
                'description' => 'Drone compact, vidéo 4K, autonomie 31 min.',
                'price' => 499.99,
                'stock' => 14,
                'main_image' => 'https://via.placeholder.com/300x300?text=Drone',
                'category' => 'Drones',
            ],
            [
                'name' => 'Disque dur externe Seagate 2To',
                'description' => 'Disque dur portable USB 3.0, 2 To.',
                'price' => 79.99,
                'stock' => 70,
                'main_image' => 'https://via.placeholder.com/300x300?text=Stockage',
                'category' => 'Stockage & Disques durs',
            ],
        ];
        // Créer une catégorie "Autres" si elle n'existe pas
        if (!isset($categoryIds['Autres'])) {
            $otherCategory = \App\Models\Category::firstOrCreate(
                ['name' => 'Autres'],
                [
                    'slug' => 'autres',
                    'description' => 'Autres produits divers',
                    'is_active' => true
                ]
            );
            $categoryIds['Autres'] = $otherCategory->id;
        }

        foreach ($products as $prod) {
            // Déterminer la catégorie du produit
            $categoryName = $prod['category'] ?? 'Autres';
            
            // Vérifier si la catégorie existe, sinon utiliser 'Autres'
            if (!isset($categoryIds[$categoryName])) {
                $category = \App\Models\Category::firstOrCreate(
                    ['name' => $categoryName],
                    [
                        'slug' => Str::slug($categoryName),
                        'description' => 'Catégorie pour ' . $categoryName,
                        'is_active' => true
                    ]
                );
                $categoryIds[$categoryName] = $category->id;
            }
            
            // Vérifier si le produit existe déjà
            $product = \App\Models\Product::where('name', $prod['name'])->first();
            
            if (!$product) {
                // Créer un nouveau produit avec les champs par défaut
                $product = new \App\Models\Product([
                    'name' => $prod['name'],
                    'slug' => Str::slug($prod['name']),
                    'description' => $prod['description'],
                    'short_description' => $prod['description'],
                    'price' => $prod['price'],
                    'sale_price' => $prod['price'] * 0.9, // 10% de réduction par défaut
                    'quantity' => rand(5, 50), // Quantité aléatoire entre 5 et 50
                    'alert_quantity' => 5,
                    'main_image' => $prod['main_image'],
                    'images' => json_encode([$prod['main_image']]),
                    'weight' => rand(1, 10) / 10, // Poids aléatoire entre 0.1 et 1.0 kg
                    'length' => rand(10, 50),
                    'width' => rand(10, 50),
                    'height' => rand(10, 50),
                    'is_active' => true,
                    'is_featured' => rand(0, 1) === 1,
                    'is_bestseller' => rand(0, 1) === 1,
                    'meta_title' => $prod['name'],
                    'meta_description' => $prod['description'],
                    'brand_id' => $brandIds[array_rand($brandIds)],
                    'category_id' => $categoryIds[$categoryName],
                    'sku' => 'PROD-' . strtoupper(Str::random(8)),
                    'barcode' => (string)rand(1000000000000, 9999999999999),
                ]);
                
                $product->save();
                
                // Associer le produit à sa catégorie
                $product->categories()->syncWithoutDetaching([$categoryIds[$categoryName]]);
            }
        }
    }
}
