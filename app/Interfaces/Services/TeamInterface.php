<?php

namespace App\Interfaces\Services;

use App\DTO\TeamData;
use App\Models\Team;
use Illuminate\Support\Collection;

/** Интерфейс сервиса команд */
interface TeamInterface
{
    /** Создать новую команду */
    public function create(TeamData $data): Team;

    /** Получить информацию о команде по идентификатору */
    public function get(int $id): Team;

    /** Получить все команды пользователя (без пагинации) */
    public function all(): Collection;

    /** Обновить команду */
    public function update(Team|int $team, TeamData $data): Team;

    /** Удалить команду */
    public function delete(Team|int $team): void;
}
