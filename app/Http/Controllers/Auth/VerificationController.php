<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Interfaces\IAuthService;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    private IAuthService $authService;
    public function __construct(IAuthService $authService)
    {
//        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
        $this->authService = $authService;
    }

    public function verify(Request $request, User $user): JsonResponse
    {
        if(!URL::hasValidSignature($request)) {
            return response()->json(["errors" => ["message" => "Invalid verification link"]], 422);
        }

        $result = $this->authService->verifyUserAccount($user);

        if(!$result) {
            return response()->json(["errors" => ["message" => "Email Address is already verified"]], 422);
        }

        return response()->json(["message" => "Email successfully verified"], 200);
    }

    public function resend(Request $request): JsonResponse
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $result = $this->authService->resendVerificationLink($request->email);

        if(!$result) {
            return response()->json(["errors" => ["message" => "Email Address is already verified"]], 422);
        }

        return response()->json(['status' => 'verification link has been resent'], 200);
    }
}

