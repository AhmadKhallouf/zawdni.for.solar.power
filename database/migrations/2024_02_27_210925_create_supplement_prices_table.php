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
        Schema::create('supplement_prices', function (Blueprint $table) {
            $table->id();
            $table->double('delivery_for_one_kiloMeter_cost')->default(0);
            $table->double('base_panel_cost')->default(0);
            $table->double('dollar_price_against_sp')->default(0);
            $table->double('one_meter_of_cables_cost')->default(0);
            $table->double('household_installation_cost')->default(0);
            $table->double('agriculture_installation_cost')->default(0);
            $table->double('industrial_installation_cost')->default(0);
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
        Schema::dropIfExists('supplement_prices');
    }
};
