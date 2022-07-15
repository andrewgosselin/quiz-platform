<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Admin Demo",
            "email" => "admin@demo.com",
            "password" => bcrypt("appletree734"),
            "is_admin" => true
        ]);
        User::create([
            "name" => "User Demo",
            "email" => "demo@user.com",
            "password" => bcrypt("appletree734"),
            "is_admin" => true
        ]);
    }
}
