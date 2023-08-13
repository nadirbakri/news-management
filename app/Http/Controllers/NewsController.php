<?php

namespace App\Http\Controllers;

use App\Events\NewsComment;
use App\Events\NewsCreated;
use App\Events\NewsDeleted;
use App\Events\NewsUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\NewsResource;
use App\Http\Resources\CommentResource;
use App\Repositories\NewsRepository;

class NewsController extends Controller
{
    protected $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function index()
    {
        $news = $this->newsRepository->get(10);

        if ($news->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No news found',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'News retrieved successfully',
            'data' => NewsResource::collection($news),
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title'   => 'required|max:255',
                'content' => 'required',
                'image'   => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $imageUrl = '';

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('news_images', 'public');
                $imageUrl = Storage::url($imagePath);
            }

            $news = $this->newsRepository->create([
                'title'   => $validatedData['title'],
                'content' => $validatedData['content'],
                'image'   => $imageUrl,
                'user_id' => auth()->user()->id,
            ]);

            event(new NewsCreated($news));

            return response()->json([
                'status' => 'success',
                'message' => 'News created successfully',
                'data' => new NewsResource($news),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->errors(),
                'data' => null,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $news = $this->newsRepository->find($id);

            return response()->json([
                'status' => 'success',
                'message' => 'News retrieved successfully',
                'data' => new NewsResource($news),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'News not found',
                'data' => null,
            ], 404);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|max:255',
                'content' => 'required',
            ]);

            $news = $this->newsRepository->update($id, $validatedData);

            event(new NewsUpdated($news));

            return response()->json([
                'status' => 'success',
                'message' => 'News updated successfully',
                'data' => new NewsResource($news),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->errors(),
                'data' => null,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occurred while updating the news',
                'data' => null,
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $news = $this->newsRepository->delete($id);

            event(new NewsDeleted($news));

            return response()->json([
                'status' => 'success',
                'message' => 'News deleted successfully',
                'data' => null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function comment(Request $request, string $id): JsonResponse
    {
        try {
            $news = $this->newsRepository->find($id);

            $validatedData = $request->validate([
                'content' => 'required',
            ]);

            $comment = $this->newsRepository->comment([
                'content'   => $validatedData['content'],
                'user_id'   => auth()->user()->id,
                'news_id'   => $news->id,
            ]);

            event(new NewsComment($news, auth()->user()->id));

            return response()->json([
                'status' => 'success',
                'message' => 'Comment created successfully',
                'data' => new CommentResource($comment),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->errors(),
                'data' => null,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
