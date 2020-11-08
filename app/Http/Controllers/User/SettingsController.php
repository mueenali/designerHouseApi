<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Grimzy\LaravelMysqlSpatial\Types\Point;


class SettingsController extends Controller
{
    //
    public function updateProfile(UpdateProfileRequest $request)
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


        return new UserResource($user);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }
}
