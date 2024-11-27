<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'gender' => 'male',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'phone' => '0999999991',
            'address' => 'homs/syria',
            'status' => 'active',
            'role' => 'admin',
        ]);

        $admin->createToken('user',['app:all'])->plainTextToken;
    }
}
