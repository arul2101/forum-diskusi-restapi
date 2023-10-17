<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->limit(1)->first();
        Post::create([
            "title" => "Ini judul test",
            "body" => "Ini body test",
            "user_id" => $user->id
        ]);
    }
}
