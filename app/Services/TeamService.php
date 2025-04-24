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

    public function create(TeamData $data): Team
    {
        $this->testPolicy('create', Team::class);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        $team = new Team((array)$data);
        $team->save();

        $this->teamLogService->log(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::TeamCreated, (array)$data)
        );

        $this->teamUserService->join($team, TeamRoleEnum::Admin);

        return $team;
    }

    public function get(int $id): Team
    {
        $team = Team::getTeam($id);

        $this->testPolicy('get', $team);

        return $team;
    }

    public function all(): Collection
    {
        $this->testPolicy('all', Team::class);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        return $currentUser->teams();
    }

    public function update(Team|int $team, TeamData $data): Team
    {
        $team = Team::getTeam($team);

        $this->testPolicy('update', $team);

        $team->update((array)$data);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        $this->teamLogService->log(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::TeamProfileUpdated, (array)$data)
        );

        return $team;
    }

    public function delete(Team|int $team): void
    {
        $team = Team::getTeam($team);

        $this->testPolicy('delete', $team);

        $team->delete();
    }
}
