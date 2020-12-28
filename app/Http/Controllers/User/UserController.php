<?php

namespace App\Http\Controllers\User;

use App\Helpers\DesignersSearchParams;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Interfaces\IUserService;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


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

     public function search(Request $request)
     {
         $params = new DesignersSearchParams(
           $request->has_designs,
           $request->available_to_hire,
           $request->langitude,
           $request->latitude,
           $request->dist,
           $request->unit,
           $request->orderByLatest
         );

         $designers = $this->userService->search($params);
         return UserResource::collection($designers);
     }

     public function getByUsername(string $username)
     {
         $user = $this->userService->getUserByUsername($username);
         return new UserResource($user);
     }

}
