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
        Schema::create('materials_quarries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materials_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('quarries_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->float('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials_quarries');
    }
};
