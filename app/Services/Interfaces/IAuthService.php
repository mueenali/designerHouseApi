<?php


namespace App\Services\Interfaces;


use App\Models\User;

interface IAuthService
{
    public function createUser(array $data): User;
    public function verifyUserAccount(User $user): bool;
    public function resendVerificationLink(string $email): bool;
}
