<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        // Création de la table stocks
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            
            // Clé étrangère vers la table products
            $table->unsignedBigInteger('product_id');
            
            // Informations de quantité
            $table->unsignedInteger('quantity')->default(0)->comment('Quantité actuelle en stock');
            $table->unsignedInteger('alert_quantity')->default(5)->comment('Seuil d\'alerte pour le réapprovisionnement');
            
            // Prix
            $table->decimal('purchase_price', 10, 2)->nullable()->comment('Prix d\'achat unitaire HT');
            $table->decimal('selling_price', 10, 2)->comment('Prix de vente unitaire HT');
            
            // Références
            $table->string('sku', 100)->nullable()->unique()->comment('Référence interne du produit');
            $table->string('barcode', 100)->nullable()->unique()->comment('Code-barres (EAN, UPC, etc.)');
            $table->string('location', 255)->nullable()->comment('Emplacement dans l\'entrepôt');
            
            // Métadonnées
            $table->text('notes')->nullable()->comment('Notes internes');
            $table->enum('status', ['in_stock', 'low_stock', 'out_of_stock'])->default('in_stock');
            
            // Suivi des modifications
            $table->unsignedBigInteger('last_updated_by')->nullable()->comment('ID du dernier utilisateur ayant modifié le stock');
            $table->timestamp('last_restocked_at')->nullable()->comment('Date du dernier réapprovisionnement');
            
            // Timestamps standards
            $table->timestamps();
            
            // Index
            $table->index('status');
            $table->index('quantity');
            $table->index('last_restocked_at');
            $table->index('product_id');
        });
        
        // Ajout des contraintes de clé étrangère après la création de la table
        Schema::table('stocks', function (Blueprint $table) {
            // Contrainte pour product_id
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
            
            // Contrainte pour last_updated_by
            $table->foreign('last_updated_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
        
        // Ajout d'un commentaire sur la table (uniquement pour MySQL)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `stocks` comment 'Gestion des stocks des produits'");
        }
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        // Suppression des contraintes de clé étrangère d'abord
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['last_updated_by']);
        });
        
        // Puis suppression de la table
        Schema::dropIfExists('stocks');
    }
};
