<?php

namespace App\Enums;

enum PolicyResultEnum: int
{
    case Unauthenticated = 0;
    case UserNotInTeam = 1;
    case NotAdmin = 2;
    case LastAdminCannotLeave = 3;
    case TeamHasMembersToRemove = 4;

    public function message(): string
    {
        return match ($this) {
            self::Unauthenticated => 'Требуется авторизация',
            self::UserNotInTeam => 'Пользователь не состоит в команде',
            self::NotAdmin => 'Пользователем не является администратором команды',
            self::LastAdminCannotLeave => 'Последний администратор не может покинуть команду',
            self::TeamHasMembersToRemove => 'Команда имеет пользователей, удаление невозможно'
        };
    }
}
