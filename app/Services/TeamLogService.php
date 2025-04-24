<?php

namespace App\Services;

use App\DTO\TeamLogData;
use App\Interfaces\Services\TeamLogInterface;
use App\Models\Team;
use App\Models\TeamLog;
use Illuminate\Database\Eloquent\Collection;

final class TeamLogService implements TeamLogInterface
{
    public function log(TeamLogData $data): void
    {
        // todo: исправить сохранение
        $logEvent = new TeamLog((array)$data);
        $logEvent->save();
    }

    public function all(Team|int $team): Collection
    {
        return TeamLog::where('team_id', $team)->get();
    }
}
