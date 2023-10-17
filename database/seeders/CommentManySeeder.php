<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentManySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("username", "test")->first();
        $post = Post::query()->limit(1)->first();

        for ( $i = 1; $i < 20; $i++) { 
            Comment::create([
                "desc" => "Ini adalah comment $i",
                "post_id" => $post->id,
                "user_id" => $user->id,
            ]);
        }
    }
}
