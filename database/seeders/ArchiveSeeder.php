<?php

namespace Database\Seeders;

use App\Models\Archive;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArchiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Archive::insert([[
        'name_of_user' => 'Ahmad Khallouf',
        'type_of_system' => 'household',
        'voltage_system' => '24',
        'number_of_inverters' => 1,
        'number_of_batteries' => 10,
        'number_of_panels' => 7,
        'distance_from_panels_to_inverter' => 20,
        'number_of_operating_hours_at_night' => 7,
        'total_day_capacity' => 1950,
        'total_night_capacity' => 2980,
        'run_way' => 'rationalization',
        'total_price' => 19500,
        'created_at' => now(),
        ],[
        'name_of_user' => 'Alex Joney',
        'type_of_system' => 'agricultural',
        'voltage_system' =>  '24',
        'number_of_inverters' => 1,
        'number_of_batteries' => null,
        'number_of_panels' => 24,
        'distance_from_panels_to_inverter' => 3,
        'number_of_operating_hours_at_night' => null,
        'total_day_capacity' => 21500,
        'total_night_capacity' => null,
        'run_way' => 'guarantee',
        'total_price' => 25000,
        'created_at' => now(),
        ],[
        'name_of_user' => 'Ali Ali',
        'type_of_system' => 'industrial',
        'voltage_system' =>  '48',
        'number_of_inverters' => 3,
        'number_of_batteries' => null,
        'number_of_panels' => 40,
        'distance_from_panels_to_inverter' => 35,
        'number_of_operating_hours_at_night' => null,
        'total_day_capacity' => 59400,
        'total_night_capacity' => null,
        'run_way' => 'guarantee',
        'total_price' => 200000,
        'created_at' => now(),
        ]]);
    }
}
