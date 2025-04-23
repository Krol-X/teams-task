<?php

namespace App\DTO;

/** Данные для создания и обновления команды */
final readonly class TeamData
{
    /**
     * @param string $name Название команды
     */
    public function __construct(
        public string $name
    )
    {
    }
}
