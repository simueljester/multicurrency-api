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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->string('base_currency', 3)->nullable();
            $table->decimal('exchange_rate', 15, 6)->nullable();
            $table->timestamp('exchange_rate_timestamp')->nullable();
            $table->decimal('amount_in_base_currency', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
