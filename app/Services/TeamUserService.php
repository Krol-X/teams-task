<?php

namespace App\Services;

use App\DTO\TeamLogData;
use App\Enums\AppExceptionsEnum;
use App\Enums\TeamLogEventEnum;
use App\Enums\TeamRoleEnum;
use App\Exceptions\AppException;
use App\Interfaces\Services\TeamLogInterface;
use App\Interfaces\Services\TeamUserInterface;
use App\Models\Team;
use App\Models\User;
use App\Traits\PolicyTestTrait;
use Illuminate\Support\Facades\Auth;

final class TeamUserService implements TeamUserInterface
{
    use PolicyTestTrait;

    public function __construct(
        public TeamService      $teamService,
        public TeamLogInterface $teamLogService
    )
    {
    }


    public function join(Team|int $team, TeamRoleEnum $role): void
    {
        $team = Team::getTeam($team);

        $this->testPolicy('join', $team);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        $team->users()->attach($currentUser);

        $this->teamLogService->log(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::UserJoined)
        );
    }

    public function leave(Team|int $team): void
    {
        $team = Team::getTeam($team);

        $this->testPolicy('leave', $team);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$team->hasUser($currentUser)) {
            throw new AppException(AppExceptionsEnum::UserNotInTeam->value);
        }

        $team->users()->detach($currentUser);

        $this->teamLogService->log(
            new TeamLogData($team, $currentUser, TeamLogEventEnum::UserLeft)
        );
    }

    public function remove(Team|int $team, User|int $user): void
    {
        $team = Team::getTeam($team);

        $this->testPolicy('remove-user', [$team, $user]);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        $teamUser = $team->getUser($user);
        $team->users()->detach($teamUser);

        $this->teamLogService->log(
            new TeamLogData($team, $teamUser, TeamLogEventEnum::UserRemoved, ['initiator' => $currentUser])
        );
    }

    public function changeRole(Team|int $team, User|int $user, TeamRoleEnum|int $newRole): void
    {
        $team = Team::getTeam($team);

        $this->testPolicy('changeRole', [$team, $user]);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser->hasRoleInTeam($team, TeamRoleEnum::Admin)) {
            throw new AppException(AppExceptionsEnum::NotAdmin->value);
        }

        $teamUser = $team->getUser($user);
        $teamUser->update(['role' => $newRole]);

        $this->teamLogService->log(
            new TeamLogData($team, $teamUser, TeamLogEventEnum::RoleChanged, ['initiator' => $currentUser])
        );
    }
}
