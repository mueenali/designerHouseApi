<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\InviteUserRequest;
use App\Services\Interfaces\IInvitationService;
use Illuminate\Http\Request;

class InvitationsController extends Controller
{
    //
    private IInvitationService $invitationService;

    public function __construct(IInvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function invite(InviteUserRequest $request ,int $team_id)
    {
        $invitation  = $this->invitationService->inviteUserToTeam($team_id, $request->email);
        return response()->json(['message' => 'Invitation sent to user', 'invitation' => $invitation]);
    }

    public function resend(int $id)
    {
        $this->invitationService->resendInvitation($id);
        return response()->json(['message' => "Invitation resent"]);
    }
}
