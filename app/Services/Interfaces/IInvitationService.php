<?php


namespace App\Services\Interfaces;


use App\Models\Invitation;

interface IInvitationService
{
    public function inviteUserToTeam(int $team_id, string $email): Invitation;

}
