<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\CommentManySeeder;
use Database\Seeders\CommentSeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function testCreateComment() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $user = User::where("username", "test")->first();
        $post = Post::query()->limit(1)->first();

        $this->post(uri: "/api/posts/$post->id/comments", data: [
            "desc" => "Ini adalah comment test"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(201)
        ->assertJson([
            "data" => [
                "desc" => "Ini adalah comment test",
                "user_id" => $user->id,
                "post_id" => $post->id,
            ]
        ]);
    }

    public function testCreateCommentFailed() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->post(uri: "/api/posts/$post->id/comments", data: [
            "desc" => ""
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "desc" => [
                    "The desc field is required."
                ]
            ]
        ]);
    }

    public function testCreatecommentNotFound() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->post(uri: "/api/posts/" . $post->id + 1 . "/comments", data: [
            "desc" => "Ini adalah comment test"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testGetComment() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();
        $user = User::where("username", "test")->first();

        $this->get(uri: "/api/posts/$post->id/comments/$comment->id", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "id" => $comment->id,
                "desc" => "Ini adalah comment test",
                "post_id" => $post->id,
                "user_id" => $user->id,
            ]
        ]);
    }

    public function testGetCommentPostIdNotFound() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->get(uri: "/api/posts/" . $post->id + 1 . "/comments/$comment->id", headers: [
            "Authorization" => "test"
        ])->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testGetCommentIdNotFound() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->get(uri: "/api/posts/$post->id/comments/" . $comment->id + 1, headers: [
            "Authorization" => "test"
        ])->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testUpdateSuccess(){
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $user = User::where("username", "test")->first();
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->patch(uri: "/api/posts/$post->id/comments/$comment->id", data: [
            "desc" => "Ini adalah comment updated"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "id" => $comment->id,
                "desc" => "Ini adalah comment updated",
                "user_id" => $user->id,
                "post_id" => $post->id,
            ]
        ]);

        $newComment = Comment::query()->limit(1)->first();

        self::assertNotEquals($comment->desc, $newComment->desc);
    }

    public function testUpdateFailed(){
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->patch(uri: "/api/posts/$post->id/comments/$comment->id", data: [
            "desc" => ""
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "desc" => [
                    "The desc field is required."
                ]
            ]
        ]);
    }

    public function testUpdateIdPostNotFound(){
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->patch(uri: "/api/posts/" . $post->id + 1 . "/comments/$comment->id", data: [
            "desc" => "Ini adalah comment test"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testUpdateIdCommentNotFound(){
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->patch(uri: "/api/posts/$post->id/comments/" . $comment->id + 1, data: [
            "desc" => "Ini adalah comment test"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testDeleteSuccess() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->delete(uri: "/api/posts/$post->id/comments/$comment->id", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => true
        ]);

        $deletedComment = Comment::query()->limit(1)->first();
        self::assertNull($deletedComment);
    }

    public function testDeleteIdPostNotFound() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->delete(uri: "/api/posts/" . $post->id + 1 . "/comments/$comment->id", headers: [
            "Authorization" => "test"
        ])->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);

        $notDeletedComment = Comment::query()->limit(1)->first();
        self::assertNotNull($notDeletedComment);
    }

    public function testDeleteIdCommentNotFound() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentSeeder::class]);
        $post = Post::query()->limit(1)->first();
        $comment = Comment::query()->limit(1)->first();

        $this->delete(uri: "/api/posts/$post->id/comments/" . $comment->id + 1, headers: [
            "Authorization" => "test"
        ])->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);

        $notDeletedComment = Comment::query()->limit(1)->first();
        self::assertNotNull($notDeletedComment);
    }

    public function testSearchWithoutQueryParam() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentManySeeder::class]);
        $post = Post::query()->limit(1)->first();

        $response = $this->get(uri: "/api/posts/$post->id/comments", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        var_dump(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response["data"]));
        self::assertEquals(1, $response["meta"]["current_page"]);
    }

    public function testWithSearchDesc() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentManySeeder::class]);
        $post = Post::query()->limit(1)->first();

        $response = $this->get(uri: "/api/posts/$post->id/comments?desc=comment 1", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        var_dump(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response["data"]));
        self::assertEquals(1, $response["meta"]["current_page"]);
    }

    public function testWithSearchDescAndPageAndSize() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentManySeeder::class]);
        $post = Post::query()->limit(1)->first();

        $response = $this->get(uri: "/api/posts/$post->id/comments?desc=comment 1&page=2&size=5", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        var_dump(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(5, count($response["data"]));
        self::assertEquals(2, $response["meta"]["current_page"]);
    }

    public function testSearchNotFoundPostId() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentManySeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->get(uri: "/api/posts/" . $post->id + 1 . "/comments", headers: [
            "Authorization" => "test"
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testSearchOtherUser() {
        $this->seed([UserSeeder::class, PostSeeder::class, CommentManySeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->get(uri: "/api/posts/$post->id/comments", headers: [
            "Authorization" => "test 2"
        ])->assertStatus(404)->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }
}
