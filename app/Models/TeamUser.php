<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/** Пользователь в команде */
class TeamUser extends Pivot
{
    protected $table = 'team_users';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'joined_at' => 'datetime',
        'role' => \App\Enums\TeamRoleEnum::class
    ];
}
