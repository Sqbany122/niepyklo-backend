<?php

namespace App\Http\Controllers\Api\v1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostCreateRequest;
use App\Models\Post;
use App\Traits\Exception\ExceptionTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    use ExceptionTrait;

    public function index()
    {
        try {
            $posts = Post::orderBy('created_at', 'DESC')->get();

            $posts->transform(function ($item) {
                foreach ($item->getMedia('images') as $media) {
                    $item['image'] = $media->getFullUrl();
                }

                return $item;
            });

            return response()->json([
                'posts' => $posts
            ]);
        } catch (\Throwable $th) {
            return $this->serverErrorException();
        }
    }

    public function store(PostCreateRequest $request): JsonResponse
    {
        try {
            DB::transaction(function () use($request) {
                $post = Post::create([
                    'title' => $request->title,
                    'category_id' => $request->category_id
                ]);

                $post->addMediaFromRequest('image')->toMediaCollection('images');
            });

            return response()->json([
                'message' => __('messages.posts.created_successfully')
            ], 200);
        } catch (\Throwable $th) {
            return $this->serverErrorException();
        }
    }
}
