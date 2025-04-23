<?php

namespace App\Services;

use App\DTO\TeamLogData;
use App\Enums\TeamLogEventEnum;
use App\Enums\TeamRoleEnum;
use App\Interfaces\Services\TeamLogInterface;
use App\Interfaces\Services\TeamUserInterface;
use App\Models\Team;
use App\Models\User;
use App\Traits\PolicyTestTrait;

// todo: переделать под актуальные политики
final class TeamUserService implements TeamUserInterface
{
    use PolicyTestTrait;

    public function __construct(
        public TeamLogInterface $teamLogService
    )
    {
    }


    public function joinTeam(Team $team, User|int $user, TeamRoleEnum $role): bool
    {
        if ($team->users->contains($user)) {
            return false;
        }
        $team->users()->attach($user);

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $user, TeamLogEventEnum::UserJoined)
        );

        return true;
    }

    public function leaveTeam(Team $team, User|int $user): void
    {
        $team->users()->detach($user);

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $user, TeamLogEventEnum::UserLeft)
        );
    }

    public function removeUserFromTeam(Team $team, User|int $user, User|int|null $initiator = null): void
    {
        $team->users()->detach($user);

        $this->teamLogService->addTeamLogEvent(
            new TeamLogData($team, $user, TeamLogEventEnum::UserRemoved, ['initiator' => $initiator])
        );
    }

    public function setUserRole(Team $team, User|int $user, $newRole, User|int|null $initiator = null): void
    {
        $teamUser = $team->getUser($user);

        if ($teamUser) {
            /** @var User $teamUser */
            $teamUser->update(['role' => $newRole]);

            $this->teamLogService->addTeamLogEvent(
                new TeamLogData($team, $teamUser, TeamLogEventEnum::RoleChanged, ['initiator' => $initiator])
            );
        }
    }
}
