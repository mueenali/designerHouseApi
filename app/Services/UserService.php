<?php


namespace App\Services;


use App\Http\Requests\UpdateProfileRequest;
use App\Services\Interfaces\IUserService;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Contracts\Auth\Authenticatable;

class UserService implements IUserService
{

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
}
