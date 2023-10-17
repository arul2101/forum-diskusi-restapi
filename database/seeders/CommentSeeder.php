<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("username", "test")->first();
        $post = Post::query()->limit(1)->first();
        Comment::create([
            "desc" => "Ini adalah comment test",
            "user_id" => $user->id,
            "post_id" => $post->id
        ]);
    }
}
