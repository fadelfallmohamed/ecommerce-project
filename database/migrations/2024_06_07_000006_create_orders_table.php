<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->decimal('total', 10, 2);
            $table->enum('status', ['en_attente', 'expediee', 'livree', 'annulee'])->default('en_attente');
            $table->enum('payment_method', ['en_ligne', 'a_la_livraison']);
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}; 