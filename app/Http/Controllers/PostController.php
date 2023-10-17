<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function create(PostCreateRequest $request): JsonResponse {
        $user = Auth::user();
        $data = $request->validated();

        $post = new Post($data);
        $post->user_id = $user->id;
        $post->save();

        return (new PostResource($post))->response()->setStatusCode(201);
    }

    public function get(int $id): PostResource {
        $user = Auth::user();
        $post = Post::where("id", $id)->where("user_id", $user->id)->first();

        if(!$post) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        return new PostResource($post);
    }

    public function update(int $id, PostUpdateRequest $request): PostResource {
        $user = Auth::user();
        $post = Post::where("id", $id)->where("user_id", $user->id)->first();
        
        if(!$post) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }
                
        $data = $request->validated();
        $post->fill($data);
        $post->save();

        return new PostResource($post);
    }

    public function delete(int $id): JsonResponse {
        $user = Auth::user();
        $post = Post::where("id", $id)->where("user_id", $user->id)->first();
        if(!$post) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        $post->delete();
        return response()->json([
            "data" => true
        ], 200);
    }

    public function search(Request $request): PostCollection {
        $size = $request->input("size", 10);
        $page = $request->input("page", 1);
        $user = Auth::user();
        $posts = Post::query()->where("user_id", $user->id);

        $posts = $posts->where(function (Builder $builder) use ($request) {
            $title = $request->input("title");
            if($title) {
                $builder->where("title", "like", "%$title%");
            }

            $body = $request->input("body");
            if($body) {
                $builder->where("body", "like", "%$body%");
            }
        });

        $posts = $posts->paginate(perPage: $size, page: $page);
        return new PostCollection($posts);
    }
}
