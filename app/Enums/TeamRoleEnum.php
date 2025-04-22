<?php

namespace App\Enums;

/** Роль пользователя в команде */
enum TeamRoleEnum: int
{
    case Member = 0;  // Простой участник
    case Admin = 1;   // Администратор
}
