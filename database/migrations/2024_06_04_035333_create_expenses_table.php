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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('expense_type', ['cleanliness', 'salaries', 'materialsTransportation' , 'other']);

            /**
             * if the expense_type => materialsTransportation
             */

            $table->unsignedBigInteger('supply_id')->nullable();
            $table->foreign('supply_id')->references('id')->on('supply')->cascadeOnDelete()->cascadeOnUpdate();
            // $table->foreignId('supply_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            /**
             * coulms for counts if expense_type => cleanliness
             */
            $table->integer('workers_number')->nullable();
            $table->integer('trucks_number')->nullable();
            $table->integer('workers_hours_number')->nullable();
            $table->integer('trucks_hours_number')->nullable();
            $table->integer('workers_hourly_price')->nullable();
            $table->integer('trucks_hourly_price')->nullable();
            $table->integer('transportation_expenses')->nullable();

            /**
             * coulms if expense_type => salary
             */
            $table->boolean('isSystemuser')->nullable();
            $table->string('userName')->nullable();
            $table->foreignId('user_salary')->nullable()->constrained('users' , 'id')->cascadeOnDelete()->cascadeOnUpdate();

            /**
             * coulms  if expense_type => other
             */
             $table->longText('description')->nullable();

             /**
             * required coulms
             */

             $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
             $table->date('date');
             $table->integer('expense')->nullable();

             $table->boolean('isPaymentMade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
