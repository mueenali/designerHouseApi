<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\IUserService;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\JsonResponse;


class SettingsController extends Controller
{
    //
    private IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }
    public function updateProfile(UpdateProfileRequest $request): UserResource
    {
        $user = $this->userService->updateProfile($request);

        return new UserResource($user);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $result = $this->userService->updatePassword($request->password);
        if($result) return response()->json(['message' => 'Password updated successfully']);

        return response()->json(['errors' => ['Password' => 'Problem in updating the password']], 400);
    }
}
