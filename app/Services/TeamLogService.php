<?php

namespace App\Services;

use App\DTO\TeamLogData;
use App\Interfaces\Services\TeamLogInterface;
use App\Models\TeamLog;

final class TeamLogService implements TeamLogInterface
{
    public function addTeamLogEvent(TeamLogData $data): void
    {
        $logEvent = new TeamLog((array)$data);
        $logEvent->save();
    }
}
