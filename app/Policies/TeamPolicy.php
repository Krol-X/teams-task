<?php

namespace App\Policies;

use App\Enums\PolicyResultEnum;
use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\User;
use App\Traits\AuthPolicyTrait;
use Illuminate\Auth\Access\Response;

final class TeamPolicy
{
    use AuthPolicyTrait;

    /** Возможность создавать новую команду */
    public function create(User $user): bool
    {
        return true; /* любой авторизованный пользователь */
    }

    /** Возможность просмотра информации о команде */
    public function view(User $user, Team $team): Response
    {
        return policyResponseFrom(
            $team->hasUser($user), PolicyResultEnum::UserNotInTeam->value
        );
    }

    /** Возможность обновления информации о команде */
    public function update(User $user, Team $team): Response
    {
        return $this->testAdmin($user, $team);
    }

    /** Возможность удаления команды */
    public function delete(Team $team): Response
    {
        return policyResponseFrom(
            $team->users()->count() == 0, PolicyResultEnum::TeamHasMembersToRemove->value
        );
    }

    /** Возможность приглашения другого пользователя */
    public function invite(User $user, Team $team): Response
    {
        return $this->testAdmin($user, $team);
    }

    /** Возможность изменения роли другого пользователя */
    public function changeUserRole(User $user, Team $team): Response
    {
        return $this->testAdmin($user, $team);
    }

    /** Возможность просмотра логов команды */
    public function viewLogs(User $user, Team $team): Response
    {
        return $this->testAdmin($user, $team);
    }

    /** Возможность покинуть команду */
    public function leave(User $user, Team $team): Response
    {
        $result = $this->testAdmin($user, $team);

        if ($result->allowed()) {
            return $this->canAdminLeave($team);
        }

        return $result;
    }

    /** Возможность выхода администратора из команды */
    public function canAdminLeave(Team $team): Response
    {
        if ($team->users()->count() == 1) {
            return Response::allow();
        }

        $admins = $team->users()->wherePivot('role', TeamRoleEnum::Admin)->count();

        return policyResponseFrom(
            $admins > 1, PolicyResultEnum::LastAdminCannotLeave->value
        );
    }

    /** Проверка роли администратора в команде */
    private function isAdmin(User $user, Team $team): bool
    {
        return $user->hasRoleInTeam($team, TeamRoleEnum::Admin);
    }

    /** Вызвать исключение, если пользователь не администратор в команде */
    private function testAdmin(User $user, Team $team): Response
    {
        if (!$this->isAdmin($user, $team)) {
            return Response::denyWithStatus(PolicyResultEnum::NotAdmin->value);
        }
        return Response::allow();
    }
}
