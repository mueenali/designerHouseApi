<?php


namespace App\Services\Interfaces;


use App\Models\Comment;

interface ICommentService
{
    public function createComment(array $data, int $design_id): Comment;
    public function updateComment(int $id, array $data): Comment;
    public function deleteComment(int $id): bool;
}
