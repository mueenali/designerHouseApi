<?php


namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function model(): string
    {
        return User::class;
    }

    public function findByEmail(string $email): User
    {
        return $this->model->where('email', $email)->first();
    }
}
