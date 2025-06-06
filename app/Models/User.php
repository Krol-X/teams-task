<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\TeamRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/** Пользователь */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /** Отправленные приглашения в команду */
    public function teamInvitesSent()
    {
        return $this->hasMany(TeamInvite::class, 'inviter_id');
    }

    /** Полученные приглашения в команду */
    public function teamInvitesReceived()
    {
        return $this->hasMany(TeamInvite::class, 'invited_id');
    }

    /** Команды, в которых состоит пользователь */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users')
            ->using(TeamUser::class)
            ->withPivot(['role', 'joined_at']);
    }

    public function hasRoleInTeam(Team $team, TeamRoleEnum $role): bool
    {
        return $team->getUser($this)?->pivot?->role == $role;
    }
}
