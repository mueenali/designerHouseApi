<?php


namespace App\Services\Interfaces;


use App\Helpers\DesignersSearchParams;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

interface IUserService
{
    public function updateProfile(UpdateProfileRequest $request): Authenticatable;
    public function updatePassword(string $password): bool;
    public function getAllUsers(): Collection;
    public function search(DesignersSearchParams $params): Collection;
    public function getUserByUsername(string $username): User;
}
