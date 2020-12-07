<?php


namespace App\Services\Interfaces;


use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

interface IUserService
{
    public function updateProfile(UpdateProfileRequest $request): Authenticatable;
    public function updatePassword(string $password): bool;
    public function getAllUsers(): Collection;
}