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
        Schema::create('supply_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customers_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->biginteger('supply_number');
            $table->integer('ton');
            $table->boolean('show')->default(true)->comment('true => Visible , false => invisible');
            $table->boolean('status')->default(true)->comment('true => Open invoice , false => Closed invoice');
            $table->longText('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_orders');
    }
};
