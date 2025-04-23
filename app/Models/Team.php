<?php

namespace App\Models;

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
    public function getUser(User|int $user): TeamUser|null
    {
        $teamUser = $this->users()->find($user);

        return $teamUser;
    }

    public function hasUser(User|int $user): bool
    {
        return $this->getUser($user) !== null;
    }
}
