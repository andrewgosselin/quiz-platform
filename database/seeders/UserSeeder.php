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
            "name" => "Demo User",
            "email" => "demo@user.com",
            "password" => bcrypt("appletree734")
        ]);
        User::create([
            "name" => "Andrew Gosselin",
            "email" => "ajg.gosselin@gmail.com",
            "password" => bcrypt("appletree734")
        ]);
    }
}
