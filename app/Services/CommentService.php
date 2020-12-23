<?php


namespace App\Services;


use App\Models\Comment;
use App\Repositories\Interfaces\ICommentRepository;
use App\Repositories\Interfaces\IDesignRepository;
use App\Services\Interfaces\ICommentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentService implements ICommentService
{
    use AuthorizesRequests;

    private IDesignRepository $designRepository;
    private ICommentRepository $commentRepository;
    public function __construct(IDesignRepository $designRepository, ICommentRepository $commentRepository)
    {
        $this->designRepository = $designRepository;
        $this->commentRepository = $commentRepository;
    }

    public function createComment(array $data, int $design_id): Comment
    {
        $design = $this->designRepository->find($design_id);

        return $design->comments()->create($data);
    }


    public function updateComment(int $id, array $data): Comment
    {
        $comment = $this->commentRepository->find($id);
        $this->authorize('update', $comment);

        return $this->commentRepository->update($id, $data);
    }

    public function deleteComment(int $id): bool
    {
        $comment = $this->commentRepository->find($id);
        $this->authorize('delete', $comment);

        return $comment->delete();
    }
}
