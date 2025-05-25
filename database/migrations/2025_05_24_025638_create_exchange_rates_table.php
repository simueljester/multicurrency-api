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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->string('from_currency', 3)->nullable();
            $table->string('to_currency', 3)->nullable();
            $table->decimal('rate', 15, 6)->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->enum('source', ['Manual', 'OpenExchangeRates'])->default('OpenExchangeRates')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
