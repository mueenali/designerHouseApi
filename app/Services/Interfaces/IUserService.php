<?php


namespace App\Services\Interfaces;


use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Contracts\Auth\Authenticatable;

interface IUserService
{
    public function updateProfile(UpdateProfileRequest $request): Authenticatable;
    public function updatePassword(string $password): bool;
}
