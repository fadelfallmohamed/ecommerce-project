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
                'nom' => 'admin',
                'prenom' => 'admin',
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
            ]);
        }

        // Ajout d'un produit exemple : Casque Bluetooth
        if (!\App\Models\Product::where('name', 'like', '%casque bluetooth%')->exists()) {
            \App\Models\Product::create([
                'name' => 'Casque Bluetooth Sony WH-1000XM4',
                'description' => 'Casque audio sans fil avec réduction de bruit, micro intégré et autonomie de 30 heures. Qualité audio exceptionnelle avec le processeur HD Noise Canceling QN1.',
                'price' => 349.99,
                'stock' => 50,
                'main_image' => 'products/casque-bluetooth-sony.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
