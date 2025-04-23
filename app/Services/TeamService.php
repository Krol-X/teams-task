<?php

namespace App\Services;

use App\DTO\TeamData;
use App\Interfaces\Services\TeamInterface;
use App\Interfaces\Services\TeamUserInterface;
use App\Models\Team;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final class TeamService implements TeamInterface
{
    public function __construct(public TeamUserInterface $teamUserService)
    {
    }

    public function addTeam(TeamData $data): Team
    {
        $team = new Team((array)$data);
        $team->save();

        $this->teamUserService->joinTeam($team, Auth::user());

        return $team;
    }

    public function getTeam(int $id): Team|null
    {
        $team = Team::find($id);

        return $team;
    }

    public function getTeams(): Collection
    {
        return Team::all();
    }

    public function updTeam(int $id, TeamData $data): Team|null
    {
        $team = $this->getTeam($id);
        $team?->update((array)$data);

        return $team;
    }

    public function delTeam(int $id): void
    {
        $team = $this->getTeam($id);
        $team?->delete();
    }
}
