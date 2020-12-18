<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\InviteUserRequest;
use App\Http\Requests\RespondRequest;
use App\Services\Interfaces\IInvitationService;


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
        return response()->json(['message' => 'Invitation sent to user', 'Invitation' => $invitation]);
    }

    public function resend(int $id)
    {
        $this->invitationService->resendInvitation($id);
        return response()->json(['message' => "Invitation resent"]);
    }

    public function respond(RespondRequest $request, int $id)
    {
        $this->invitationService->respond($request->token, $request->decision, $id);
        return response()->json(['message' => 'Successful']);
    }

    public function delete(int $id)
    {
        $result = $this->invitationService->deleteInvitation($id);

        if(!$result)
        {
            return response()->json(['errors' => ['Invitation'=> 'Problem in deleting the invitation']], 400);
        }

        return response()->json(['message' => 'Invitation deleted successfully']);
    }

}
