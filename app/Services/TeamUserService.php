<?php

namespace App\Services;

use App\Enums\TeamRoleEnum;
use App\Interfaces\Services\TeamUserInterface;
use App\Models\Team;
use App\Models\User;

final class TeamUserService implements TeamUserInterface
{
    public function joinTeam(Team $team, User|int $user, TeamRoleEnum $role): bool
    {
        if ($team->users()->isAttached($user)) {
            return false;
        }
        $team->users()->attach($user);

        return true;
    }

    public function leaveTeam(Team $team, User|int $user): void
    {
        $team->users()->detach($user);
    }

    public function removeUserFromTeam(Team $team, User|int $user): void
    {
        $team->users()->detach($user);
    }

    public function setUserRole(Team $team, User|int $user, $newRole): void
    {
        $team->users()->where('user_id', $user->id)
            ->update(['role' => $newRole]);
    }
}
