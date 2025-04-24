<?php

namespace App\Interfaces\Services;

use App\Enums\TeamRoleEnum;
use App\Models\Team;
use App\Models\User;

/** Интерфейс сервиса управления пользователями в команде */
interface TeamUserInterface
{
    /** Присоединиться к команде */
    public function join(Team|int $team, TeamRoleEnum $role): void;

    /** Покинуть команду */
    public function leave(Team|int $team): void;

    /** Удалить пользователя из команды */
    public function remove(Team|int $team, User|int $user): void;

    /** Назначить роль пользователю */
    public function changeRole(Team|int $team, User|int $user, TeamRoleEnum|int $newRole): void;
}
