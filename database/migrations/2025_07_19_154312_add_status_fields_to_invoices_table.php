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
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('status', ['pending', 'signed', 'cancelled'])->default('pending')->after('invoice_date');
            $table->foreignId('signed_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
            $table->timestamp('signed_at')->nullable()->after('signed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['signed_by']);
            $table->dropColumn(['status', 'signed_by', 'signed_at']);
        });
    }
};
