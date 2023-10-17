<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class CommentController extends Controller
{
    public function getPost(User $user, int $idPost): Post {
        $post = Post::where("id", $idPost)->where("user_id", $user->id)->first();
        if(!$post) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        return $post;
    }

    public function getComment(int $idPost, int $idComment, User $user): Comment {
        $comment = Comment::where("id", $idComment)->where("post_id", $idPost)->where("user_id", $user->id)->first();
        if(!$comment) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "not found"
                    ]
                ]
            ], 404));
        }

        return $comment;
    }

    public function create(int $idPost, CommentCreateRequest $request): JsonResponse {
        $user = Auth::user();
        $post = $this->getPost($user, $idPost);

        $data = $request->validated();

        $comment = new Comment($data);
        $comment->user_id = $user->id;
        $comment->post_id = $idPost;
        $comment->save();

        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    public function get(int $idPost, int $idComment) {
        $user = Auth::user();
        $post = $this->getPost($user, $idPost);

        $comment = $this->getComment($idPost, $idComment, $user);

        return new CommentResource($comment);
    }

    public function update(int $idPost, int $idComment, CommentUpdateRequest $request): CommentResource {
        $user = Auth::user();
        $post = $this->getPost($user, $idPost);

        $comment = $this->getComment($idPost, $idComment, $user);

        $data = $request->validated();
        $comment->desc = $data["desc"];
        $comment->save();

        return new CommentResource($comment);
    }

    public function delete(int $idPost, int $idComment): JsonResponse {
        $user = Auth::user();
        $post = $this->getPost($user, $idPost);

        $comment = $this->getComment($idPost, $idComment, $user);

        $comment->delete();
        return response()->json([
            "data" => true
        ], 200);
    }

    public function search(int $idPost, Request $request): CommentCollection {
        $size = $request->input("size", 10);
        $page = $request->input("page", 1);
        $user = Auth::user();
        $post = $this->getPost($user, $idPost);

        $comments = Comment::query()->where("post_id", $idPost)->where("user_id", $user->id);

        $comments = $comments->where(function (Builder $builder) use ($request) {
            $desc = $request->input("desc");
            if($desc) {
                $builder->where("desc", "like", "%$desc%");
            }
        });

        $comments = $comments->paginate(perPage: $size, page: $page);
        return new CommentCollection($comments);
    }
}
