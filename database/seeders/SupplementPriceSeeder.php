<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplementPrice;

class SupplementPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SupplementPrice::create([
            'delivery_for_one_kiloMeter_cost' => 0,
            'base_panel_cost' => 0,
            'dollar_price_against_sp' =>0,
            'one_meter_of_cables_cost'=>0,
            'household_installation_cost'=>0,
            'agriculture_installation_cost'=>0,
            'industrial_installation_cost'=>0,
        ]);
        
    }
}
