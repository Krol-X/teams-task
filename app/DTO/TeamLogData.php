<?php

namespace App\DTO;

use App\Enums\TeamLogEventEnum;
use App\Models\Team;
use App\Models\User;

/** Данные для логирования событий команды */
final readonly class TeamLogData
{
    /**
     * @param string $name Название команды
     */
    public function __construct(
        public Team             $team,
        public User             $user,
        public TeamLogEventEnum $eventType,
        public array|null       $data = null
    )
    {
    }
}
