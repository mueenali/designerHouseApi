<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public function attemptLogin(Request $request): bool
    {
        $token = $this->guard()->attempt($this->credentials($request));
        if(!$token) {
            return false;
        }

        $user  = $this->guard()->user();
        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return false;
        }

        $this->guard()->setToken($token);

        return true;
    }

    protected function sendLoginResponse(Request $request): JsonResponse
    {
        $this->clearLoginAttempts($request);
        $token = (string)$this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json(['token' => $token, 'tokenType' => 'bearer', 'expires_in' => $expiration]);
    }

    protected function sendFailedLoginResponse(): JsonResponse
    {
        $user = $this->guard()->user();

        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            return response()->json(['errors' => ['verification' => 'You Need to verify your account']], 422);
        }

        throw ValidationException::withMessages([$this->username() => "Invalid credentials"]);
    }

    public function logout(): JsonResponse
    {
        $this->guard()->logout();
        return response()->json(['message' => 'logged out successfully'], 200);
    }
}
