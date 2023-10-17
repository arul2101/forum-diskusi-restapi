<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "username" => "test",
            "password" => Hash::make("rahasia"),
            "name" => "test",
            "token" => "test"
        ]);

        User::create([
            "username" => "test 2",
            "password" => Hash::make("rahasia"),
            "name" => "test 2",
            "token" => "test 2"
        ]);
    }
}
