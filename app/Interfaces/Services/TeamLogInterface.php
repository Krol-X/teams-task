<?php

namespace App\Interfaces\Services;

use App\DTO\TeamLogData;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

/** Интерфейс сервиса команд */
interface TeamLogInterface
{
    /** Логировать событие команды */
    public function addTeamLogEvent(TeamLogData $data): void;

    /** Получить лог команды (без пагинации) */
    public function getTeamLog(Team|int $team): Collection;
}
