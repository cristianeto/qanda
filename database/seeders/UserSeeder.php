<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::Truncate();

        $user1 = new User;
        $user1->name = "Guest";
        $user1->email = "guest@test.com";
        $user1->password = Hash::make('password');
        $user1->save();

        $user2 = new User;
        $user2->name = "Cris";
        $user2->email = "cris@test.com";
        $user2->password = Hash::make('password');
        $user2->save();
    }
}
