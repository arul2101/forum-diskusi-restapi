<?php

namespace Tests\Feature;

use App\Models\Post;
use Database\Seeders\PostManySeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function testCreatePostSuccess() {
        $this->seed([UserSeeder::class]);
        $this->post(uri: "/api/posts", data: [
            "title" => "Ini judul test",
            "body" => "Ini body test ya gaes"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(201)
        ->assertJson([
            "data" => [
                "title" => "Ini judul test",
                "body" => "Ini body test ya gaes"
            ]
        ]);
    }

    public function testCreatePostFailed() {
        $this->seed([UserSeeder::class]);
        $this->post(uri: "/api/posts", data: [
            "title" => "Ini judul test",
            "body" => "I"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "body" => [
                    "The body field must be at least 10 characters."
                ]
            ]
        ]);
    }

    public function testCreatePostUnauthorized() {
        $this->post(uri: "/api/posts", data: [
            "title" => "Ini judul test",
            "body" => "I"
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]);
    }

    public function testGetPostSuccess() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->get(uri: "/api/posts/$post->id", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
            ->assertJson([
                "data" => [
                    "id" => $post->id,
                    "title" => $post->title,
                    "body" => $post->body,
                    "createdAt" => $post->created_at->toJSON(),
                    "updatedAt" => $post->updated_at->toJSON(),
                ]
            ]);
    }

    public function testGetPostUnauthorized() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->get(uri: "/api/posts/$post->id", headers: [
            "Authorization" => "salah"
        ])->assertStatus(401)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "unauthorized"
                    ]
                ]
            ]);
    }

    public function testGetOtherUser() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->get(uri: "/api/posts/$post->id", headers: [
            "Authorization" => "test 2"
        ])->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ]);
    }

    public function testGetUserNotFound() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->get(uri: "/api/posts/" . $post->id + 1, headers: [
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

    public function testUpdateSucces() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->put(uri: "/api/posts/$post->id", data: [
            "title" => "Ini title updated"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "id" => $post->id,
                "title" => "Ini title updated",
                "body" => $post->body
            ]
        ]);
    }

    public function testUpdateUnauthorized() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->put(uri: "/api/posts/$post->id", data: [
            "title" => "Ini title updated"
        ], headers: [
            "Authorization" => "salah"
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]);
    }

    public function testUpdateNotFound() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->put(uri: "/api/posts/" . $post->id + 1, data: [
            "title" => "Ini title updated"
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

    public function testUpdateFailed() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();

        $this->put(uri: "/api/posts/$post->id", data: [
            "body" => "I"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "body" => [
                    "The body field must be at least 10 characters."
                ]
            ]
        ]);
    }

    public function testDeleteSuccess() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();
        
        $this->delete(uri: "/api/posts/$post->id", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => true
        ]);

        $post = Post::query()->limit(1)->first();
        self::assertNull($post);
    }

    public function testDeleteUnauthorized() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();
        
        $this->delete(uri: "/api/posts/$post->id", headers: [
            "Authorization" => "salah"
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]);

        $post = Post::query()->limit(1)->first();
        self::assertNotNull($post);
    }

    public function testDeleteOtherUser() {
        $this->seed([UserSeeder::class, PostSeeder::class]);
        $post = Post::query()->limit(1)->first();
        
        $this->delete(uri: "/api/posts/$post->id", headers: [
            "Authorization" => "test 2"
        ])->assertStatus(404)
        ->assertJson([
            "errors" => [
                "message" => [
                    "not found"
                ]
            ]
        ]);
    }

    public function testSearchByTitle() {
        $this->seed([UserSeeder::class, PostManySeeder::class]);
        
        $response = $this->get(uri: "/api/posts?title=title 1", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        var_dump(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response["data"]));
        self::assertEquals(11, $response["meta"]["total"]);
    }

    public function testSearchByBody() {
        $this->seed([UserSeeder::class, PostManySeeder::class]);
        
        $response = $this->get(uri: "/api/posts?body=body 1", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        var_dump(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response["data"]));
        self::assertEquals(11, $response["meta"]["total"]);
    }

    public function testSearchByBodyWithPage() {
        $this->seed([UserSeeder::class, PostManySeeder::class]);
        
        $response = $this->get(uri: "/api/posts?body=body 1&page=2&size=5", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)->json();

        var_dump(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(5, count($response["data"]));
        self::assertEquals(2, $response["meta"]["current_page"]);
    }

    
}
