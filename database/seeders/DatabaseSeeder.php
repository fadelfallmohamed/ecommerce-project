<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Création de l'utilisateur admin s'il n'existe pas
        if (!\App\Models\User::where('email', 'admin@example.com')->exists()) {
            \App\Models\User::create([
                'nom' => 'Admin',
                'prenom' => 'Système',
                'name' => 'Admin Système',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'is_admin' => true,
            ]);
            
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: admin123');
        } else {
            // Mettre à jour l'utilisateur admin existant pour s'assurer qu'il a les bons droits
            $admin = \App\Models\User::where('email', 'admin@example.com')->first();
            if ($admin) {
                $admin->update(['is_admin' => true]);
                $this->command->info('Existing admin user updated with admin privileges.');
            }
        }

        // Ajout de produits exemples pour chaque catalogue (13 produits seulement)
        $catalogueProducts = [
            [
                'name' => 'Téléviseur LG OLED 55"',
                'description' => 'Téléviseur OLED 4K Ultra HD, HDR, Smart TV, 55 pouces.',
                'price' => 1299.99,
                'stock' => 10,
                'main_image' => 'https://via.placeholder.com/300x300?text=TV',
                'category' => 'Téléviseurs',
            ],
            [
                'name' => 'Tablette Samsung Galaxy Tab S8',
                'description' => 'Tablette Android 11 pouces, 128 Go, Wi-Fi.',
                'price' => 599.99,
                'stock' => 20,
                'main_image' => 'https://via.placeholder.com/300x300?text=Tablette',
                'category' => 'Tablettes',
            ],
            [
                'name' => 'Imprimante HP LaserJet Pro',
                'description' => 'Imprimante laser monochrome, Wi-Fi, impression rapide.',
                'price' => 199.99,
                'stock' => 15,
                'main_image' => 'https://via.placeholder.com/300x300?text=Imprimante',
                'category' => 'Imprimantes',
            ],
            [
                'name' => 'Souris Logitech MX Master 3',
                'description' => 'Souris sans fil ergonomique, rechargeable, multi-appareils.',
                'price' => 89.99,
                'stock' => 50,
                'main_image' => 'https://via.placeholder.com/300x300?text=Accessoire',
                'category' => 'Accessoires',
            ],
            [
                'name' => 'Console PlayStation 5',
                'description' => 'Console de jeux nouvelle génération, 825 Go SSD, manette DualSense.',
                'price' => 549.99,
                'stock' => 8,
                'main_image' => 'https://via.placeholder.com/300x300?text=Console',
                'category' => 'Consoles de jeux',
            ],
            [
                'name' => 'iPhone 13 Bleu 128 Go',
                'description' => 'iPhone 13, écran 6,1", puce A15 Bionic, double appareil photo.',
                'price' => 899.99,
                'stock' => 25,
                'main_image' => 'https://via.placeholder.com/300x300?text=iPhone+13',
                'category' => 'iPhone 13',
            ],
            [
                'name' => 'iPhone 14 Pro 256 Go',
                'description' => 'iPhone 14 Pro, écran 6,1", triple appareil photo, 5G.',
                'price' => 1199.99,
                'stock' => 18,
                'main_image' => 'https://via.placeholder.com/300x300?text=iPhone+14',
                'category' => 'iPhone 14',
            ],
            [
                'name' => 'Montre connectée Apple Watch SE',
                'description' => 'Montre intelligente, suivi santé, GPS, notifications.',
                'price' => 299.99,
                'stock' => 30,
                'main_image' => 'https://via.placeholder.com/300x300?text=Montre',
                'category' => 'Montres intelligentes',
            ],
            [
                'name' => 'Appareil photo Canon EOS 250D',
                'description' => 'Reflex numérique, capteur 24,1 MP, vidéo 4K.',
                'price' => 649.99,
                'stock' => 12,
                'main_image' => 'https://via.placeholder.com/300x300?text=Appareil+photo',
                'category' => 'Appareils photo',
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
        foreach ($catalogueProducts as $prod) {
            $product = \App\Models\Product::firstOrCreate([
                'name' => $prod['name'],
            ], [
                'description' => $prod['description'],
                'price' => $prod['price'],
                'stock' => $prod['stock'],
                'main_image' => $prod['main_image'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $category = \App\Models\Category::where('name', $prod['category'])->first();
            if ($category) {
                $product->categories()->syncWithoutDetaching([$category->id]);
            }
        }
    }
}
