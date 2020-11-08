<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request, User $user)
    {
        if(!URL::hasValidSignature($request)) {
            return response()->json(["errors" => ["message" => "Invalid verification link",]], 422);
        }

        if($user->hasVerifiedEmail()) {
            return response()->json(["errors" => ["message" => "Email Address is already verified",]], 422);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return response()->json(["message" => "Email successfully verified"], 200);
    }

    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json(['errors' => ['email' => 'No user found with this email address']], 422);
        }

        if($user->hasVerifiedEmail()) {
            return response()->json(["errors" => ["message" => "Email Address is already verified",]], 422);
        }

        $user->sendEmailVerificationNotification();
        return response()->json(['status' => 'verification link resent'], 200);
    }
}

