<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained();
            $table->foreignUuid('gateway_id')->constrained();
            $table->string('external_id')->nullable();
            $table->string('status', 30);
            $table->decimal('amount');
            $table->string('card_last_numbers', 4);
            $table->timestamps();
        });

        Schema::create('transaction_products', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->foreignUuid('transaction_id')->constrained();
            $table->foreignUuid('product_id')->constrained();
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_products');
        Schema::dropIfExists('transactions');
    }
};
