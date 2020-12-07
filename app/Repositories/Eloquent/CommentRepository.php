<?php


namespace App\Repositories\Eloquent;


use App\Models\Comment;
use App\Repositories\Interfaces\ICommentRepository;

class CommentRepository extends BaseRepository implements ICommentRepository
{
    public function model()
    {
        return Comment::class;
    }

}
