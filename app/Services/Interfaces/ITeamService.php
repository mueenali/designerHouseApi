<?php


namespace App\Services\Interfaces;


use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

interface ITeamService
{
    public function getAllTeams(): Collection;
    public function createTeam(array $data): Team;
    public function findById(int $id): Team;
    public function findBySlug(string $slug): Team;
    public function getUserTeams(): Collection;
    public function updateTeam(int $id, array $data): Team;
    public function deleteTeam(int $id): bool;
}
