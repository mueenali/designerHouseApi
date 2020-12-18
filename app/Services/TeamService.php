<?php


namespace App\Services;


use App\Models\Team;
use App\Repositories\Interfaces\ITeamRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Interfaces\ITeamService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\UnauthorizedException;

class TeamService implements ITeamService
{

    use AuthorizesRequests;
    private ITeamRepository $teamRepository;
    private IUserRepository $userRepository;

    public function __construct(ITeamRepository $teamRepository, IUserRepository $userRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllTeams(): Collection
    {
        return $this->teamRepository->all();
    }

    public function createTeam(array $data): Team
    {
        return $this->teamRepository->create($data);
    }

    public function findById(int $id): Team
    {
        return $this->teamRepository->find($id);
    }

    public function findBySlug(string $slug): Team
    {
        return $this->teamRepository->findWhereFirst('slug', $slug);
    }

    public function getUserTeams(): Collection
    {
        return auth()->user()->teams();
    }

    public function updateTeam(int $id, array $data): Team
    {
        $team = $this->teamRepository->find($id);
        $this->authorize('update', $team);

        return $this->teamRepository->update($id, $data);
    }

    public function deleteTeam(int $id): bool
    {
        $team = $this->teamRepository->find($id);
        $this->authorize('delete', $team);

        return $this->teamRepository->delete($id);
    }

    public function removeMember(int $id, int $user_id): bool
    {
        $team = $this->teamRepository->find($id);
        $user = $this->userRepository->find($user_id);

        if($user->isOwnerOfTeam($team))
        {
            throw new UnauthorizedException('Cannot remove the team owner');
        }

        $this->authorize('ownerOfTeam', $team);

        return $team->removeUserFromTeam($user_id);
    }

}
