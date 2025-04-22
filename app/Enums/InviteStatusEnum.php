<?php

namespace App\Enums;

/** Статус приглашения в команду */
enum InviteStatusEnum: int
{
    case await = 0;     // Ожидание решения пользователя
    case accepted = 1;  // Приглашение принято
    case declined = 2;  // Приглашение отклонено
}
