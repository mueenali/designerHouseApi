<?php

use App\Models\Team;
use \Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\UnauthorizedException;

function send_email(string $email, Mailable $mail): void
{
    Mail::to($email)->send($mail);
}

function is_owner_of_team(Team $team): void
{
    $user = auth()->user();
    if(! $user->isOwnerOfTeam($team))
    {
        throw new UnauthorizedException('You are not the owner of the team', 401);
    }
}
