<?php

namespace App\Services;

use App\Interfaces\Services\InvitationInterface;
use App\Models\Team;
use App\Models\User;

final class InvitationService implements InvitationInterface
{
    public function inviteUserToTeam(Team $team, User|int $user)
    {

    }
}
