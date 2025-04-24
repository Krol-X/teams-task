<?php

namespace App\Traits;

use App\Enums\AppExceptionsEnum;
use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

trait AuthPolicyTrait
{
    /** Допускать на проверку только авторизированных пользователей */
    public function before(User|null $user, string $ability): Response|null
    {
        if ($user === null) {
            return Response::denyWithStatus(AppExceptionsEnum::Unauthenticated->value);
        }
        return null;
    }

    /** Проверка роли администратора в команде */
    private function isAdmin(User $user, Team $team): bool
    {
        return $user->hasRoleInTeam($team, TeamRoleEnum::Admin);
    }
}
