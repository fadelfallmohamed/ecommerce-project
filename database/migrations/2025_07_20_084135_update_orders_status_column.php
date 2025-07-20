<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Mettre à jour les valeurs existantes vers le nouveau format
        \DB::table('orders')
            ->where('status', 'expediee')
            ->update(['status' => 'en_cours_de_livraison']);
            
        \DB::table('orders')
            ->where('status', 'livree')
            ->update(['status' => 'livrée']);
            
        \DB::table('orders')
            ->where('status', 'annulee')
            ->update(['status' => 'annulée']);
        
        // Modifier la colonne avec les nouvelles valeurs autorisées en utilisant du SQL brut
        $sql = "ALTER TABLE orders 
                MODIFY COLUMN status ENUM('en_attente', 'en_cours_de_livraison', 'livrée', 'annulée') 
                CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci 
                NOT NULL DEFAULT 'en_attente'";
                
        \DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Revenir aux anciennes valeurs si nécessaire
        \DB::table('orders')
            ->where('status', 'en_cours_de_livraison')
            ->update(['status' => 'expediee']);
            
        \DB::table('orders')
            ->where('status', 'livrée')
            ->update(['status' => 'livree']);
            
        \DB::table('orders')
            ->where('status', 'annulée')
            ->update(['status' => 'annulee']);
        
        // Revenir à l'ancien format de la colonne en utilisant du SQL brut
        $sql = "ALTER TABLE orders 
                MODIFY COLUMN status ENUM('en_attente', 'expediee', 'livree', 'annulee') 
                CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci 
                NOT NULL DEFAULT 'en_attente'";
                
        \DB::statement($sql);
    }
};
