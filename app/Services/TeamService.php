<?php

namespace App\Services;

use App\DTO\TeamData;
use App\DTO\TeamLogData;
use App\Enums\AppExceptionsEnum;
use App\Enums\TeamLogEventEnum;
use App\Enums\TeamRoleEnum;
use App\Exceptions\AppException;
use App\Interfaces\Services\TeamInterface;
use App\Interfaces\Services\TeamLogInterface;
use App\Interfaces\Services\TeamUserInterface;
use App\Models\Team;
use App\Models\User;
use App\Traits\PolicyTestTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final class TeamService implements TeamInterface
{
    use PolicyTestTrait;

    public function __construct(
        public TeamUserInterface $teamUserService,
        public TeamLogInterface  $teamLogService
    )
    {
    }

    public function addTeam(TeamData $data): Team
    {
        $this->testPolicy('addTeam');

        /** @var User $currentUser */
        $currentUser = Auth::user();

        $team = new Team((array)$data);
        $team->save();

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::TeamCreated, (array)$data)
        );

        $this->teamUserService->joinTeam($team, TeamRoleEnum::Admin);

        return $team;
    }

    public function getUserTeam(int $id): Team
    {
        $team = Team::find($id) ?? throw new AppException(AppExceptionsEnum::TeamNotFound->value);

        $this->testPolicy('getUserTeam', $team);

        return $team;
    }

    public function getUserTeams(): Collection
    {
        $this->testPolicy('getUserTeams');

        /** @var User $currentUser */
        $currentUser = Auth::user();

        return $currentUser->teams();
    }

    public function updTeam(Team|int $team, TeamData $data): Team
    {
        if (is_int($team)) {
            $team = $this->getUserTeam($team);
        }

        $this->testPolicy('updTeam', $team);

        $team->update((array)$data);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::TeamProfileUpdated, (array)$data)
        );

        return $team;
    }

    public function delTeam(Team|int $team): void
    {
        if (is_int($team)) {
            $team = $this->getUserTeam($team);
        }

        $this->testPolicy('delTeam', $team);

        $team->delete();
    }
}
