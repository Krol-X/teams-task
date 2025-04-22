<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Приглашение в команду */
class TeamInvite extends Model
{
    public $timestamps = false;

    protected $fillable = ['invited_id', 'inviter_id', 'team_id', 'status'];

    protected $casts = [
        'status' => \App\Enums\InviteStatusEnum::class
    ];

    /** Приглашённый пользователь */
    public function invited()
    {
        return $this->belongsTo(User::class);
    }

    /** Приглашающий пользователь */
    public function inviter()
    {
        return $this->belongsTo(User::class);
    }

    /** Команда */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
