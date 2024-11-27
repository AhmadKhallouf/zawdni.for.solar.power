<?php

namespace Database\Seeders;

use App\Models\Damage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DamagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Damage::insert([[
            'type_of_inverter' => 'household',
            'manufacture_company' => 'opento',
            'model_of_inverter' => 'M12',
            'watt' => 1200,
            'code' => 'CCNA',
            'description' => 'the power of batteries is low',
            'solution' => 'recharge the battery',
        ],[
            'type_of_inverter' => 'household',
            'manufacture_company' => 'opento',
            'model_of_inverter' => 'M12',
            'watt' => 1200,
            'code' => 'CCMN',
            'description' => 'the power of panels is low, it is possible because weather condition',
            'solution' => 'you have to wait until climate improvement',
        ],[
            'type_of_inverter' => 'household',
            'manufacture_company' => 'amazon',
            'model_of_inverter' => 'C14',
            'watt' => 2500,
            'code' => 'MHC11',
            'description' => 'there is damage in your inverter',
            'solution' => 'preferred engineer`s intervention',
        ],[
            'type_of_inverter' => 'agricultural',
            'manufacture_company' => 'Ropaza',
            'model_of_inverter' => 'K_12_25',
            'watt' => 1500,
            'code' => 'ZZIOP',
            'description' => 'there is low power from panels',
            'solution' => 'preferred to change your panels ',
        ],[
            'type_of_inverter' => 'agricultural',
            'manufacture_company' => 'Ropaza',
            'model_of_inverter' => 'K_12_25',
            'watt' => 1500,
            'code' => 'ZQIOP',
            'description' => 'there is a low electric from the adapter',
            'solution' => 'preferred to restart the inverter',
        ],[
            'type_of_inverter' => 'industrial',
            'manufacture_company' => 'Korba',
            'model_of_inverter' => '4685',
            'watt' => 125000,
            'code' => 'ARtcno',
            'description' => 'there is unexpected volt from the loads',
            'solution' => 'switch of the inverter and check your machines',
        ],[
            'type_of_inverter' => 'industrial',
            'manufacture_company' => 'Korba',
            'model_of_inverter' => '4685',
            'watt' => 125000,
            'code' => 'ARtcNM',
            'description' => 'one of the cable does not work as normal',
            'solution' => 'preferred engineer`s intervention',
        ]]);
    }
}
