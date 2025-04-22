<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Лог (история) событий в команде */
class TeamLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['team_id', 'user_id', 'event_type', 'data'];

    protected $casts = [
        'event' => \App\Enums\HistoryEventEnum::class,
        'data' => 'array',
    ];

    /** Команда */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /** Инициатор действия */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
