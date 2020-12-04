<?php


namespace App\Services;


use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IAuthService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Intervention\Image\Exception\NotFoundException;

class AuthService implements IAuthService
{
    private IUserRepository $userRepository;
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function verifyUserAccount(User $user) : bool
    {
        if($user->hasVerifiedEmail()) {
            return false;
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return true;
    }

    public function resendVerificationLink(string $email): bool
    {
        $user = $this->userRepository->findWhereFirst('email', $email);

        if(!$user) {
             throw new ModelNotFoundException();
        }

        if($user->hasVerifiedEmail()) {
          return false;
        }

        $user->sendEmailVerificationNotification();
        return true;
    }
}
