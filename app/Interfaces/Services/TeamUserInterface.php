<?php

namespace App\Interfaces\Services;

/** Интерфейс сервиса управления пользователями в команде */
interface TeamUserInterface
{
    /** Присоединиться к команде */
    public function joinTeam($user, $team): bool;
    /** Покинуть команду */
    public function leaveTeam($user, $team): void;
    /** Удалить пользователя из команды */
    public function removeUserFromTeam($user, $team): void;
    /** Назначить роль пользователю */
    public function setUserRole($team, $user, $newRole): void;
}
