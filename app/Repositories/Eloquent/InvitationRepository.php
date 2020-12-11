<?php


namespace App\Repositories\Eloquent;


use App\Models\Invitation;
use App\Repositories\Interfaces\IInvitationRepository;

class InvitationRepository extends BaseRepository implements IInvitationRepository
{
    public function model()
    {
        return Invitation::class;
    }
}
