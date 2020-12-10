<?php


namespace App\Repositories\Eloquent;


use App\Models\Team;
use App\Repositories\Interfaces\ITeamRepository;

class TeamRepository extends BaseRepository implements ITeamRepository
{
    public function model(): string
    {
        return Team::class;
    }
}
