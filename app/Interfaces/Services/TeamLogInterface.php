<?php

namespace App\Interfaces\Services;

use App\DTO\TeamLogData;

/** Интерфейс сервиса команд */
interface TeamLogInterface
{
    /** Логировать событие команды */
    public function addTeamLogEvent(TeamLogData $data): void;
}
