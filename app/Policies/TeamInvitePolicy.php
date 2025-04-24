<?php

namespace App\Policies;

use App\Enums\AppExceptionsEnum;
use App\Enums\InviteStatusEnum;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use App\Traits\AuthPolicyTrait;
use Illuminate\Auth\Access\Response;

/** Политика приглашений в команду */
class TeamInvitePolicy
{
    use AuthPolicyTrait;

    /** Возможность приглашать пользователя в команду */
    public function invite(User $currentUser, TeamInvite $invite, Team $team): Response
    {
        return policyResponseFrom(
            $this->isAdmin($currentUser, $team), AppExceptionsEnum::UserNotInTeam->value
        );
    }

    /** Возможность отвечать на приглашение в команду */
    public function respond(User $currentUser, TeamInvite $invite): Response
    {
        if ($invite->invited_id !== $currentUser->id) {
            return Response::denyWithStatus(AppExceptionsEnum::NotInvitationOwner->value);
        }

        return match ($invite->status) {
            InviteStatusEnum::Accepted => Response::denyWithStatus(AppExceptionsEnum::InvitationAlreadyAccepted->value),
            InviteStatusEnum::Declined => Response::denyWithStatus(AppExceptionsEnum::InvitationAlreadyRejected->value),
            // InviteStatusEnum::Cancelled => throw new AppException(AppExceptionsEnum::InvitationCancelled->value),
            default => Response::allow()
        };
    }

    /** Возможность смотреть на доступные приглашения */
    public function getPendingInvitations(User $currentUser, TeamInvite $invite): Response
    {
        return Response::allow(); /* любой авторизованный пользователь */
    }
}
