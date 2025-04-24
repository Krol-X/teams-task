<?php

namespace App\Services;

use App\DTO\TeamLogData;
use App\Enums\InviteStatusEnum;
use App\Enums\TeamLogEventEnum;
use App\Enums\TeamRoleEnum;
use App\Interfaces\Services\InvitationInterface;
use App\Interfaces\Services\TeamLogInterface;
use App\Interfaces\Services\TeamUserInterface;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use App\Traits\PolicyTestTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final class InvitationService implements InvitationInterface
{
    use PolicyTestTrait;

    public function __construct(
        public TeamUserInterface $teamUserService,
        public TeamLogInterface  $teamLogService
    )
    {
    }

    public function invite(Team|int $team, User|int $user): void
    {
        $team = Team::getTeam($team);

        $this->testPolicy('invite', [TeamInvite::class, $team]);

        TeamInvite::create([
            'invited_id' => $user?->id ?? $user,
            'inviter_id' => Auth::id(),
            'team_id' => $team->id,
            'status' => InviteStatusEnum::Pending
        ]);

        /** @var User $currentUser */
        $currentUser = Auth::user();

        // Логирование
        $this->teamLogService->log(
            new TeamLogData(
                $team,
                $user, // todo: fix this for int
                TeamLogEventEnum::UserInvited,
                ['initiator' => $currentUser]
            )
        );
    }

    public function respond(TeamInvite $invite, bool $isAccepted): void
    {
        $this->testPolicy('respond', TeamInvite::class);

        $invite->update([
            'status' => $isAccepted
                ? InviteStatusEnum::Accepted
                : InviteStatusEnum::Declined
        ]);

        if ($isAccepted) {
            $this->teamUserService->join(
                $invite->team,
                TeamRoleEnum::Member
            );
        }
    }

    public function getPendingInvitations(): Collection
    {
        $this->testPolicy('get-pending-invitations', TeamInvite::class);

        /** @var User $user */
        $user_id = Auth::id();

        return TeamInvite::with(['team', 'inviter'])
            ->where('invited_id', $user_id)
            ->where('status', InviteStatusEnum::Pending)
            ->get();
    }
}
