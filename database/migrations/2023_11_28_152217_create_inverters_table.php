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
        Schema::create('inverters', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['household','agricultural','industrial']);
            $table->string('manufacture_company');
            $table->string('model')->nullable();
            $table->double('watt');
            $table->text('description')->nullable();
            $table->double('price');
            $table->unsignedInteger('quantity_available');
            $table->string('photo');
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
        Schema::dropIfExists('inverters');
    }
};
