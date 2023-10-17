<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostManySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("username", "test")->first();
        for ( $i = 1; $i <= 20; $i++ ) { 
            Post::create([
                "title" => "Ini adalah title $i",
                "body" => "Ini adalah body $i",
                "user_id" => $user->id
            ]);
        }
    }
}
