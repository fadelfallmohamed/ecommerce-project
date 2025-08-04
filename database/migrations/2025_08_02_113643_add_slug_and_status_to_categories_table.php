<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->boolean('is_active')->default(true)->after('description');
            
            // Ajout d'un index sur le slug pour les performances
            $table->index('slug');
        });
        
        // Mettre à jour les catégories existantes avec des slugs
        \Illuminate\Support\Facades\DB::table('categories')->get()->each(function ($category) {
            \Illuminate\Support\Facades\DB::table('categories')
                ->where('id', $category->id)
                ->update([
                    'slug' => \Illuminate\Support\Str::slug($category->name),
                    'is_active' => true
                ]);
        });
        
        // Rendre le champ slug obligatoire après la mise à jour
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropColumn(['slug', 'is_active']);
        });
    }
};
