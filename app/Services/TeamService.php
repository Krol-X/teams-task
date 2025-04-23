<?php

namespace App\Services;

use App\DTO\TeamData;
use App\DTO\TeamLogData;
use App\Enums\TeamLogEventEnum;
use App\Enums\TeamRoleEnum;
use App\Interfaces\Services\TeamInterface;
use App\Interfaces\Services\TeamLogInterface;
use App\Interfaces\Services\TeamUserInterface;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final class TeamService implements TeamInterface
{
    public function __construct(
        public TeamUserInterface $teamUserService,
        public TeamLogInterface  $teamLogService
    )
    {
    }

    public function addTeam(TeamData $data): Team
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();
        // todo: check User auth, refactor

        $team = new Team((array)$data);
        $team->save();

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::TeamCreated, (array)$data)
        );

        $this->teamUserService->joinTeam($team, $currentUser, TeamRoleEnum::Admin);

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
        /** @var User $currentUser */
        $currentUser = Auth::user();
        // todo: check User auth, refactor

        $team = $this->getTeam($id);
        $team?->update((array)$data);

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::TeamProfileUpdated, (array)$data)
        );

        return $team;
    }

    public function delTeam(int $id): void
    {
        $team = $this->getTeam($id);
        $team?->delete();
    }
}
