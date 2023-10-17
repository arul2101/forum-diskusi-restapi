<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function register(UserCreateRequest $request): JsonResponse {
        $data = $request->validated();

        $user = User::where("username", $data["username"])->first();

        if(isset($user)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username already registered"
                    ]
                ]
            ], 400));
        }

        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): UserResource {
        $data = $request->validated();

        $user = User::where("username", $data["username"])->first();
        
        if(!isset($user) || !Hash::check($data["password"], $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "username or password is wrong"
                    ]
                ]
            ], 400));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function get(): UserResource {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request): JsonResponse {
        $user = Auth::user();
        $data = $request->validated();

        if(isset($data["name"])) {
            $user->name = $data["name"];
        }

        if(isset($data["password"])) {
            $user->password = Hash::make($data["password"]);
        }
        
        $user->save();
        return response()->json([
            "data" => [
                "id" => $user->id,
                "username" => $user->username,
                "name" => $user->name
            ]
        ], 200);
    }

    public function logout(): JsonResponse {
        $user = Auth::user();
        $user->token = null;
        $user->save();

        return response()->json([
            "data" => true
        ], 200);
    }
}
