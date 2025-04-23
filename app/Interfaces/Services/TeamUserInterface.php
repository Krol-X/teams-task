<?php

namespace App\Interfaces\Services;

use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\User;

/** Интерфейс сервиса управления пользователями в команде */
interface TeamUserInterface
{
    /** Присоединиться к команде */
    public function joinTeam(Team $team, User|int $user, TeamRoleEnum $role): bool;

    /** Покинуть команду */
    public function leaveTeam(Team $team, User|int $user): void;

    /** Удалить пользователя из команды */
    public function removeUserFromTeam(Team $team, User|int $user): void;

    /** Назначить роль пользователю */
    public function setUserRole(Team $team, User|int $user, TeamRoleEnum $newRole): void;
}
