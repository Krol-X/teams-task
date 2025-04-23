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

    /** Получить команду по идентификатору */
    public function getTeam(int $id): Team|null;

    /** Получить все команды (без пагинации) */
    public function getTeams(): Collection;

    /** Обновить команду */
    public function updTeam(int $id, TeamData $data): Team|null;

    /** Удалить команду */
    public function delTeam(int $id): void;
}
