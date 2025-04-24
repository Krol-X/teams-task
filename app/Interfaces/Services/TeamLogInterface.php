<?php

namespace App\Interfaces\Services;

use App\DTO\TeamLogData;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

/** Интерфейс сервиса команд */
interface TeamLogInterface
{
    /** Логировать событие команды */
    public function log(TeamLogData $data): void;

    /** Получить лог команды (без пагинации) */
    public function all(Team|int $team): Collection;
}
