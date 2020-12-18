<?php


namespace App\Services;


use App\Mail\SendInvitationToJoinTeam;
use App\Models\Invitation;
use App\Repositories\Interfaces\IInvitationRepository;
use App\Repositories\Interfaces\ITeamRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IInvitationService;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\UnauthorizedException;

class InvitationService implements IInvitationService
{
    use AuthorizesRequests;

    private IInvitationRepository $invitationRepository;
    private ITeamRepository $teamRepository;
    private IUserRepository $userRepository;

    public function __construct(
        IInvitationRepository $invitationRepository,
        ITeamRepository $teamRepository,
        IUserRepository $userRepository
    )
    {
        $this->invitationRepository = $invitationRepository;
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
    }

    public function inviteUserToTeam(int $team_id, string $email): Invitation
    {
        $team = $this->teamRepository->find($team_id);

        $this->authorize('ownerOfTeam', $team);

        if($team->hasPendingInvite($email))
        {
            throw new ValidationException('Email already has a pending invite');
        }

        $recipient = $this->userRepository->findByEmail($email);

        if(! $recipient)
        {
            return $this->createInvitation(false , $team->id, $email);
        }

        if($team->hasUser($recipient))
        {
            throw new ValidationException('The user is already a member of the team');
        }

        return $this->createInvitation(true ,$team->id, $email);
    }


    public function resendInvitation(int $id): void
    {
        $invitation = $this->invitationRepository->find($id);
        $recipient = $this->userRepository->findByEmail($invitation->recipient_email);

        $this->authorize('ownerOfTeam', $invitation->team);

        send_email($invitation->recipient_email,
            new SendInvitationToJoinTeam($invitation, !is_null($recipient)));
    }

    public function respond(string $token, bool $decision, int $id): void
    {
        $invitation = $this->invitationRepository->find($id);

        $this->authorize('respond', $invitation);


        if($invitation->token != $token)
        {
            throw new UnauthorizedException('Invalid Token');
        }

        if($decision)
        {
            $invitation->team->addUserToTeam(auth()->id());
        }

        $this->invitationRepository->delete($id);
    }

    public function deleteInvitation(int $id): bool
    {
         $invitation = $this->invitationRepository->find($id);
         $this->authorize('delete', $invitation);

         return $this->invitationRepository->delete($id);
    }


    private function createInvitation(bool $user_exists, int $team_id, string $email): Invitation
    {
        $invitation = $this->invitationRepository->create([
            'recipient_email' => $email,
            'sender_id' => auth()->id(),
            'team_id' => $team_id,
            'token' => md5(uniqid(microtime()))
        ]);

        send_email($email, new SendInvitationToJoinTeam($invitation, $user_exists));

        return $invitation;
    }

}

