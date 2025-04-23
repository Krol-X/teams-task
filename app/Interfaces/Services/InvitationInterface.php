<?php

namespace App\Interfaces\Services;

/** Интерфейс сервиса приглашений */
interface InvitationInterface
{
    /** Пригласить пользователя в команду */
    public function inviteUserToTeam($user, $team);
}
