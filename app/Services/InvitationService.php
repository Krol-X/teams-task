<?php

namespace App\Services;

use App\Interfaces\Services\InvitationInterface;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use Ramsey\Collection\Collection;

final class InvitationService implements InvitationInterface
{
    public function invite(Team|int $team, User|int $user): void
    {
        //
    }

    public function respond(TeamInvite $invite, bool $isAccepted): void
    {
        //
    }

    public function getPendingInvitations(): Collection
    {
        //
    }
}
