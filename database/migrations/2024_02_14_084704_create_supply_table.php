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
        Schema::create('supply', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 32)->unique();
            $table->foreignId('quarries_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('transportation_companies_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('transport_workers_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('customers_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('supply_orders_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('materials_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->integer('cost_of_transporting_a_ton');
            $table->integer('opening_amount');
            $table->longText('branch');
            $table->foreignId('Company_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('ton')->nullable();
            $table->integer('price_per_ton')->nullable();
            $table->integer('profit')->nullable();
            $table->integer('total_invoice')->nullable();
            $table->enum('status', ['invoiced', 'Collected', 'PartialCollection', 'shipped' , 'delivered' , 'cancelled'])->default('shipped');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply');
    }
};
