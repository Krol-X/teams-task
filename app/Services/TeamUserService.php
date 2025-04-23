<?php

namespace App\Services;

use App\Interfaces\Services\TeamUserInterface;

final class TeamUserService implements TeamUserInterface
{
    public function joinTeam($user, $team): bool
    {
        if ($team->users()->where('user_id', $user->id)->exists()) {
            return false;
        }
        $team->users()->attach($user);

        return true;
    }

    public function leaveTeam($user, $team): void
    {
        $team->users()->detach($user);
    }

    public function removeUserFromTeam($user, $team): void
    {
        $team->users()->detach($user);
    }

    public function setUserRole($team, $user, $newRole): void
    {
        $team->users()->where('user_id', $user->id)
            ->update(['role' => $newRole]);
    }
}
