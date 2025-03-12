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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('movement_type', ['checkin', 'checkout']);
            $table->double('quantity');
            $table->decimal('value', 12, 2)->nullable();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade'); // Adicionado
            $table->foreignId('company_id')->constrained('companies', 'id')
            ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('item_in_stock_id')->constrained('items_in_stock')->cascadeOnUpdate()->cascadeOnDelete(); // 🔥 Aqui está o vínculo
            $table->timestamps();
            $table->index('movement_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
