<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\Interfaces\ICommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    //
    private ICommentService $commentService;
    public function __construct(ICommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request, int $design_id): CommentResource
    {
        $comment = $this->commentService->createComment([
            'body' => $request->body,
            'user_id' => auth()->id()
        ], $design_id);

        return new CommentResource($comment);
    }

    public function update(StoreCommentRequest $request ,int $id): CommentResource
    {
        $comment = $this->commentService->updateComment($id, ['body' => $request->body]);
        return new CommentResource($comment);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->commentService->deleteComment($id);

        if(!$result) {
            return response()->json(['errors' => ['comment' => 'Problem in deleting the comment']], 400);
        }

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}
