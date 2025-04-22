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
        return $this->hasMany(TeamInvitie::class);
    }

    /** История событий команды */
    public function logs()
    {
        return $this->hasMany(TeamLog::class);
    }
}
