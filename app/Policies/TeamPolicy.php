<?php

namespace App\Policies;

use App\Enums\AppExceptionsEnum;
use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use App\Traits\AuthPolicyTrait;
use Illuminate\Auth\Access\Response;

/** Политика команды */
final class TeamPolicy
{
    use AuthPolicyTrait;

    // TeamService

    /** Возможность создавать новую команду */
    public function create(User $currentUser, Team $team): Response
    {
        return Response::allow(); /* любой авторизованный пользователь */
    }

    /** Возможность просмотра подробной информации о команде */
    public function get(User $currentUser, Team $team): Response
    {
        return policyResponseFrom(
            $team->hasUser($currentUser), AppExceptionsEnum::UserNotInTeam->value
        );
    }

    /** Возможность просмотра команд, в которых состоит пользователь */
    public function all(User $currentUser, Team $team): Response
    {
        return Response::allow(); /* любой авторизованный пользователь */
    }

    /** Возможность обновления информации о команде */
    public function update(User $currentUser, Team $team): Response
    {
        return policyResponseFrom(
            $this->isAdmin($currentUser, $team), AppExceptionsEnum::NotAdmin->value
        );
    }

    /** Возможность удаления команды */
    public function delete(User $currentUser, Team $team): Response
    {
        return policyResponseFrom(
            $team->users()->count() == 0, AppExceptionsEnum::TeamHasMembersToRemove->value
        );
    }

    // TeamUserService

    /** Возможность присоединения к команде */
    public function join(User $currentUser, TeamUser $tu, Team $team): Response
    {
        return policyResponseFrom(
            !$team->hasUser($currentUser), AppExceptionsEnum::UserAlwaysInTeam->value
        );
    }

    /** Возможность покинуть команду */
    public function leave(User $currentUser, TeamUser $tu, Team $team): Response
    {
        $teamUser = $team->getUser($currentUser);

        $isAdmin = $teamUser->pivot?->role == TeamRoleEnum::Admin;

        if ($isAdmin) {
            /* пользователь один в команде? */
            if ($team->users()->count() > 1) {
                return Response::denyWithStatus(AppExceptionsEnum::LastAdminCannotLeave->value);
            }

            /* пользователь единственный админ? */
            $adminsCount = $team->users()->wherePivot('role', TeamRoleEnum::Admin)->count();
            if ($adminsCount == 1) {
                return Response::denyWithStatus(AppExceptionsEnum::LastAdminCannotLeave->value);
            }
        }

        return Response::allow();
    }

    /** Возможность удаления пользователя из команды */
    public function removeUser(User $currentUser, TeamUser $tu, Team $team, User $user): Response
    {
        return policyResponseFrom(
            $this->isAdmin($currentUser, $team), AppExceptionsEnum::NotAdmin->value
        );
    }

    /** Возможность изменения роли другого пользователя */
    public function changeRole(User $currentUser, TeamUser $tu, Team $team): Response
    {
        return policyResponseFrom(
            $this->isAdmin($currentUser, $team), AppExceptionsEnum::NotAdmin->value
        );
    }
}
