<?php

namespace App\Enums;

enum AppExceptionsEnum: int
{
    case Unauthenticated = 0;
    case UserNotInTeam = 1;
    case NotAdmin = 2;
    case LastAdminCannotLeave = 3;
    case TeamHasMembersToRemove = 4;
    case UserAlwaysInTeam = 5;
    case TeamNotFound = 6;
    case InvitationAlreadyAccepted = 7;
    case InvitationAlreadyRejected = 8;
    case NotInvitationOwner = 9;

    public function message(): string
    {
        return match ($this) {
            self::Unauthenticated => 'Требуется авторизация',
            self::UserNotInTeam => 'Пользователь не состоит в команде',
            self::NotAdmin => 'Пользователем не является администратором команды',
            self::LastAdminCannotLeave => 'Последний администратор не может покинуть команду',
            self::TeamHasMembersToRemove => 'Команда имеет пользователей, удаление невозможно',
            self::UserAlwaysInTeam => 'Пользователь уже в команде',
            self::TeamNotFound => 'Команда не найдена',
            self::InvitationAlreadyAccepted => 'Приглашение уже принято',
            self::InvitationAlreadyRejected => 'Приглашение уже отклонено',
            self::NotInvitationOwner => 'Вы не являетесь владельцем этого приглашения'
        };
    }
}
