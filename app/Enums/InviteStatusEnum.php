<?php

namespace App\Enums;

/** Статус приглашения в команду */
enum InviteStatusEnum: int
{
    case Pending = 0;     // Ожидание решения пользователя
    case Accepted = 1;  // Приглашение принято
    case Declined = 2;  // Приглашение отклонено
}
