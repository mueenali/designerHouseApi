<?php


namespace App\Services\Interfaces;


use App\Models\Design;
use Illuminate\Http\Request;

interface IDesignService
{
    public function upload($image): Design;
    public function update(Request $request, int $id): Design;
    public function delete(int $id): bool;
}
