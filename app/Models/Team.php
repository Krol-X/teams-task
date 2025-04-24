<?php

namespace App\Models;

use App\Enums\AppExceptionsEnum;
use App\Exceptions\AppException;
use Illuminate\Database\Eloquent\Model;

/** Команда */
class Team extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    /** Приглашения в команду */
    public function invitations()
    {
        return $this->hasMany(TeamInvite::class);
    }

    /** История событий команды */
    public function logs()
    {
        return $this->hasMany(TeamLog::class);
    }

    /** Пользователи в команде */
    public function users()
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->using(TeamUser::class)
            ->withPivot(['role', 'joined_at']);
    }

    /** Получить пользователя из команды */
    public function getUser(User|int $user): User
    {
        $teamUser = $this->users()->find($user);

        if (is_null($teamUser)) {
            throw new AppException(AppExceptionsEnum::UserNotInTeam->value);
        }

        return $teamUser;
    }

    public function hasUser(User|int $user): bool
    {
        return $this->users->contains($user);
    }
}
