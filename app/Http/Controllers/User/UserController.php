<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\IUserService;


class UserController extends Controller
{
    //
    private IUserService $userService;
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

     public function getCurrentUser()
     {
         if(auth()->check()) {
             $user = auth()->user();
             return new UserResource($user);
         }

         return response()->json(null, 401 );
     }

     public function index()
     {
         $users  = $this->userService->getAllUsers();
         return UserResource::collection($users);
     }

}
