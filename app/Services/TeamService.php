<?php

namespace App\Services;

use App\DTO\TeamData;
use App\DTO\TeamLogData;
use App\Enums\TeamLogEventEnum;
use App\Enums\TeamRoleEnum;
use App\Exceptions\PolicyResultException;
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

    /**
     * @throws PolicyResultException
     */
    public function addTeam(TeamData $data): Team
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        $this->testPolicy('create', Team::class);

        $team = new Team((array)$data);
        $team->save();

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::TeamCreated, (array)$data)
        );

        $this->teamUserService->joinTeam($team, $currentUser, TeamRoleEnum::Admin);

        return $team;
    }

    /**
     * @throws PolicyResultException
     */
    public function getTeam(int $id): Team|null
    {
        $this->testPolicy('view', Team::class);

        $team = Team::find($id);

        return $team;
    }

    public function getTeams(): Collection
    {
        // todo: нужно иметь возможность получать информацию лишь о своих командах
        // $this->testPolicy('viewAny');

        return Team::all();
    }

    /**
     * @throws PolicyResultException
     */
    public function updTeam(int $id, TeamData $data): Team|null
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        $this->testPolicy('update', Team::class);

        $team = $this->getTeam($id);
        $team?->update((array)$data);

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::TeamProfileUpdated, (array)$data)
        );

        return $team;
    }

    /**
     * @throws PolicyResultException
     */
    public function delTeam(int $id): void
    {
        $this->testPolicy('delete', Team::class);

        $team = $this->getTeam($id);
        $team?->delete();
    }
}
