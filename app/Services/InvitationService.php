<?php


namespace App\Services;


use App\Mail\SendInvitationToJoinTeam;
use App\Models\Invitation;
use App\Repositories\Interfaces\IInvitationRepository;
use App\Repositories\Interfaces\ITeamRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\IInvitationService;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\UnauthorizedException;

class InvitationService implements IInvitationService
{
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

        is_owner_of_team($team);

        if($team->hasPendingInvite($email))
        {
            throw new ValidationException('Email already has a pending invite', 422);
        }

        $recipient = $this->userRepository->findByEmail($email);

        if(! $recipient)
        {
            return $this->createInvitation(false , $team->id, $email);
        }

        if($team->hasUser($recipient))
        {
            throw new ValidationException('The user is already a member of the team', 422);
        }

        return $this->createInvitation(true ,$team->id, $email);
    }


    public function resendInvitation(int $id): void
    {
        $invitation = $this->invitationRepository->find($id);
        $recipient = $this->userRepository->findByEmail($invitation->recipient_email);

        is_owner_of_team($invitation->team);

        send_email($invitation->recipient_email,
            new SendInvitationToJoinTeam($invitation, !is_null($recipient)));
    }

    private function createInvitation(bool $user_exists, int $team_id, string $email): Invitation
    {
        $invitation = $this->invitationRepository->create([
            'team_id' => $team_id,
            'sender_id' => auth()->id(),
            'recipient_email' => $email,
            'token' => md5(uniqid(microtime()))
        ]);

        send_email($email, new SendInvitationToJoinTeam($invitation, $user_exists));

        return $invitation;
    }




}

