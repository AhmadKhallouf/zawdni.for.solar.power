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
        Schema::create('damages', function (Blueprint $table) {
            $table->id();
            $table->enum('type_of_inverter',['household','agricultural','industrial']);
            $table->string('manufacture_company');
            $table->string('model_of_inverter');
            $table->double('watt');
            $table->string('code'); 
            $table->text('description');
            $table->text('solution');
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
        Schema::dropIfExists('damages');
    }
};
