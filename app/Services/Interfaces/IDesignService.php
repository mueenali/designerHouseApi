<?php


namespace App\Services\Interfaces;


use App\Helpers\DesignSearchParams;
use App\Models\Design;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface IDesignService
{
    public function upload($image): Design;
    public function update(Request $request, int $id): Design;
    public function delete(int $id): bool;
    public function getAllDesigns(): Collection;
    public function findDesign(int $id): Design;
    public function likeDesign(int $id): bool;
    public function isLikedByUser(int $id): bool;
    public function search(DesignSearchParams $params): Collection;
}
