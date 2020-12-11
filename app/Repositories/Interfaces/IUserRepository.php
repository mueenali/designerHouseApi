<?php


namespace App\Repositories\Interfaces;


use App\Models\User;

interface IUserRepository
{
    public function findByEmail(string $email): User;
}
