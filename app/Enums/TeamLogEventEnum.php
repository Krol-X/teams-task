<?php

namespace App\Enums;

/** Тип события в логе команды */
enum TeamLogEventEnum: int
{
    case Uncategorized = 0;  // Без категории

    // 1–9: события, связанные с командой
    case TeamCreated = 1;         // Создание команды
    case TeamProfileUpdated = 2;  // Обновление профиля команды

    // 10–19: управление участниками
    case UserInvited = 10;  // Приглашение пользователя
    case UserJoined = 11;   // Пользователь принял приглашение
    case UserRemoved = 12;  // Удаление пользователя
    case UserLeft = 13;     // Пользователь сам вышел
    case RoleChanged = 14;  // Изменение роли
}
