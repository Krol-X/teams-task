<?php

namespace App\Interfaces\Services;

use App\DTO\TeamData;
use App\Models\Team;
use Illuminate\Support\Collection;

/** Интерфейс сервиса команд */
interface TeamInterface
{
    /** Создать новую команду */
    public function addTeam(TeamData $data): Team;

    /** Получить информацию о команде по идентификатору */
    public function getUserTeam(int $id): Team;

    /** Получить все команды пользователя (без пагинации) */
    public function getUserTeams(): Collection;

    /** Обновить команду */
    public function updTeam(Team|int $team, TeamData $data): Team;

    /** Удалить команду */
    public function delTeam(Team|int $team): void;
}
