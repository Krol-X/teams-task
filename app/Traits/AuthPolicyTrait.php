<?php

namespace App\Traits;

use App\Enums\AppExceptionsEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/** Допускать на проверку только авторизированных пользователей */
trait AuthPolicyTrait
{
    public function before(User|null $user, string $ability): Response|null
    {
        if ($user === null) {
            return Response::denyWithStatus(AppExceptionsEnum::Unauthenticated->value);
        }
        return null;
    }
}
