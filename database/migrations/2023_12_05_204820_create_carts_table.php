<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->enum('type_of_system',['household','agricultural','industrial']);
            $table->enum('voltage_system',['12','24','48'])->nullable();
            $table->unsignedInteger('number_of_inverters')->nullable();
            $table->unsignedInteger('number_of_batteries')->nullable();
            $table->unsignedInteger('number_of_panels')->nullable();
            $table->unsignedInteger('distance_from_panels_to_inverter')->nullable();
            $table->unsignedInteger('number_of_operating_hours_at_night')->nullable();
            $table->unsignedInteger('total_day_capacity')->nullable();
            $table->unsignedInteger('total_night_capacity')->nullable();
            $table->enum('run_way',['guarantee','rationalization','micro_consumerism'])->nullable();
            $table->double('total_price')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status',['incomplete','processing','accepted','refused','delivered','archived']);
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};
