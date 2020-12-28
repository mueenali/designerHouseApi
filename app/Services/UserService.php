<?php


namespace App\Services;


use App\Helpers\DesignersSearchParams;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\SearchDesigners;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IUserService;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class UserService implements IUserService
{
    private IUserRepository $userRepository;
    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function updateProfile(UpdateProfileRequest $request): Authenticatable
    {
        $user = auth()->user();
        $location = new Point($request->location['latitude'], $request->location['longitude']);

        $user->update([
            'name' => $request->name,
            'formatted_address' => $request->formatted_address,
            'available_to_hire' => $request->available_to_hire,
            'about' => $request->about,
            'tagline' => $request->tagline,
            'location' => $location
        ]);

        return $user;
    }

    public function updatePassword(string $password): bool
    {
       $user = auth()->user();

       if($user->update(['password' => bcrypt($password)]))
       {
           return true;
       }

       return false;
    }

    public function getAllUsers(): Collection
    {
        return $this->userRepository->withCriteria([new EagerLoad(['designs'])])->all();
    }

    public function search(DesignersSearchParams $params): Collection
    {
        return $this->userRepository->withCriteria([new SearchDesigners($params)])->all();
    }

    public function getUserByUsername(string $username): User
    {
        return $this->userRepository->findWhereFirst('username', $username);
    }
}
