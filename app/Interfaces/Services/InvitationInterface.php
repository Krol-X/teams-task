<?php

namespace App\Interfaces\Services;

use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use Ramsey\Collection\Collection;

/** Интерфейс сервиса приглашений */
interface InvitationInterface
{
    /** Пригласить пользователя в команду */
    public function invite(Team|int $team, User|int $user): void;

    /** Ответить на приглашение в команду */
    public function respond(TeamInvite $invite, bool $isAccepted): void;

    /** Получить приглашения пользователя */
    public function getPendingInvitations(): Collection;
}
