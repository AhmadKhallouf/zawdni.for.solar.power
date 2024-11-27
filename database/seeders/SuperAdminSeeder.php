<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        
        //create super admin account automatically
        $super_admin = User::create([
                            'first_name' => 'Super',
                            'last_name' => 'Admin',
                            'gender' => 'NULL',
                            'email' => 'zodni.for.solar.power@gmail.com',
                            'email_verified_at' => now(),
                            'password' => Hash::make('Zodni555@'),
                            'phone' => '0999999990',
                            'address' => 'Akrama/homs/syria',
                            'status' => 'active',
                            'role' => 'super_admin',
                        ]);

        $super_admin->createToken('user',['app:all'])->plainTextToken;
    }
}
