<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateMissingStocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stocks:create-missing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crée des enregistrements de stock pour les produits qui n\'en ont pas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminUser = User::where('nom', 'admin')->first();
        
        if (!$adminUser) {
            $this->error('Aucun utilisateur admin trouvé. Veuillez créer un utilisateur admin d\'abord.');
            return 1;
        }

        $productsWithoutStock = Product::doesntHave('stock')->get();
        
        if ($productsWithoutStock->isEmpty()) {
            $this->info('Tous les produits ont déjà un enregistrement de stock.');
            return 0;
        }

        $this->info(sprintf('Création des enregistrements de stock pour %d produits...', $productsWithoutStock->count()));
        
        $bar = $this->output->createProgressBar($productsWithoutStock->count());
        $bar->start();
        
        $created = 0;
        
        foreach ($productsWithoutStock as $product) {
            try {
                // Créer un enregistrement de stock avec des valeurs par défaut
                $stock = new Stock([
                    'quantity' => 10, // Quantité par défaut
                    'alert_quantity' => 5, // Seuil d'alerte par défaut
                    'selling_price' => $product->price, // Utiliser le prix du produit comme prix de vente
                    'status' => 'in_stock',
                    'last_updated_by' => $adminUser->id,
                    'last_restocked_at' => now(),
                ]);
                
                // Associer le stock au produit
                $product->stock()->save($stock);
                $created++;
                
            } catch (\Exception $e) {
                $this->error(sprintf('Erreur lors de la création du stock pour le produit ID %d: %s', 
                    $product->id, $e->getMessage()));
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        
        $this->newLine(2);
        $this->info(sprintf('%d enregistrements de stock créés avec succès.', $created));
        
        if ($created < $productsWithoutStock->count()) {
            $this->warn(sprintf('%d échecs lors de la création des stocks.', 
                $productsWithoutStock->count() - $created));
        }
        
        return 0;
    }
}
