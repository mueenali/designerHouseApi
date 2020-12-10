<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Resources\TeamResource;
use App\Services\Interfaces\ITeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamsController extends Controller
{
    //

    private ITeamService $teamService;

    public function __construct(ITeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index()
    {
        $teams = $this->teamService->getAllTeams();
        return TeamResource::collection($teams);
    }

    public function store(StoreTeamRequest $request): TeamResource
    {
        $team = $this->teamService->createTeam([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    public function findById(int $id): TeamResource
    {
        $team = $this->teamService->findById($id);
        return new TeamResource($team);
    }

    public function findBySlug(string $slug): TeamResource
    {
        $team = $this->teamService->findBySlug($slug);
        return new TeamResource($team);
    }

    public function getUserTeams()
    {
        $teams = $this->teamService->getUserTeams();
        return TeamResource::collection($teams);
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:80', 'unique:teams,name'.$id]
        ]);

        $team = $this->teamService->updateTeam($id, [
           'name' => $request->name,
           'slug' => Str::slug($request->name)
        ]);

        return new TeamResource($team);
    }

    public function delete(int $id)
    {
        $result = $this->teamService->deleteTeam($id);

        if(!$result)
            return response()->json(['errors'=> ['team' => 'Problem in deleting the team']], 400);

        return response()->json(['message' => 'Successfully deleted the team']);
    }

}
