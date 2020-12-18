<?php


namespace App\Services\Interfaces;


use App\Models\Invitation;


interface IInvitationService
{
    public function inviteUserToTeam(int $team_id, string $email): Invitation;
    public function resendInvitation(int $id): void;
    public function respond(string $token, bool $decision, int $id): void;
    public function deleteInvitation(int $id): bool;

}
