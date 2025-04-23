<?php

namespace App\Interfaces\Services;

use App\Models\Team;
use App\Models\User;

/** Интерфейс сервиса приглашений */
interface InvitationInterface
{
    /** Пригласить пользователя в команду */
    public function inviteUserToTeam(Team $team, User|int $user);
}
