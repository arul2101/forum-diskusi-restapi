<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    
    public function testRegisterSuccess() {
        $this->post('/api/users', [
            "username" => "test",
            "password" => "rahasia",
            "name" => "test"
        ])->assertStatus(201)
        ->assertJson([
            "data" => [
                "username" => "test",
                "name" => "test"
            ]
        ]);
    }

    public function testRegisterDuplicate() {
        $this->seed([UserSeeder::class]);

        $this->post('/api/users', [
            "username" => "test",
            "password" => "rahasia",
            "name" => "test"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "username" => [
                    "username already registered"
                ]
            ]
        ]);
    }

    public function testRegisterFailed() {
        $this->post('/api/users', [
            "username" => "",
            "password" => "rahasia",
            "name" => "test"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "username" => [
                    "The username field is required."
                ]
            ]
        ]);
    }

    public function testLoginSuccess() {
        $this->seed([UserSeeder::class]);

        $this->post(uri: "/api/users/login", data: [
            "username" => "test",
            "password" => "rahasia"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "username" => "test",
                "name" => "test",
            ]
        ]);
    }

    public function testLoginUsernameWrong() {
        $this->seed([UserSeeder::class]);

        $this->post(uri: "/api/users/login", data: [
            "username" => "salah",
            "password" => "rahasia"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "message" => [
                    "username or password is wrong"
                ]
            ]
        ]);
    }

    public function testLoginPasswordWrong() {
        $this->seed([UserSeeder::class]);

        $this->post(uri: "/api/users/login", data: [
            "username" => "test",
            "password" => "salah"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "message" => [
                    "username or password is wrong"
                ]
            ]
        ]);
    }

    public function testLoginFailed() {
        $this->seed([UserSeeder::class]);

        $this->post(uri: "/api/users/login", data: [
            "username" => "",
            "password" => "salah"
        ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                "username" => [
                    "The username field is required."
                ]
            ]
        ]);
    }

    public function testGetUserSuccess() {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "username" => "test",
                "name" => "test",
                "token" => "test"
            ]
        ]);
    }

    public function testGetUserTokenWrong() {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current", headers: [
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

    public function testGetUserTokenBlank() {
        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current", headers: [
            "Authorization" => ""
        ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "message" => [
                    "unauthorized"
                ]
            ]
        ]);
    }

    public function testUpdatePasswordSuccess() {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where("username", "test")->first();

        $this->patch("/api/users/current", data: [
            "password" => "passwordUpdated"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "id" => $oldUser->id,
                "username" => "test",
                "name" => "test",
            ]
        ]);

        $newUser = User::where("username", "test")->first();
        self::assertNotEquals($newUser->password, $oldUser->password);
    }

    public function testUpdateUsernameSuccess() {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where("username", "test")->first();

        $this->patch("/api/users/current", data: [
            "name" => "test updated"
        ], headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => [
                "id" => $oldUser->id,
                "username" => "test",
                "name" => "test updated",
            ]
        ]);

        $newUser = User::where("username", "test")->first();
        self::assertNotEquals($newUser->name, $oldUser->name);
    }

    public function testUpdateUnauthorized() {
        $this->seed([UserSeeder::class]);

        $this->patch("/api/users/current", data: [
            "password" => "passwordUpdated"
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

    public function testLogoutSuccess() {
        $this->seed([UserSeeder::class]);

        $this->delete("/api/users/logout", headers: [
            "Authorization" => "test"
        ])->assertStatus(200)
        ->assertJson([
            "data" => true
        ]);

        $user = User::where("username", "test")->first();
        self::assertNull($user->token);
    }

    public function testLogoutUnauthorized() {
        $this->seed([UserSeeder::class]);

        $this->delete("/api/users/logout", headers: [
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

}
