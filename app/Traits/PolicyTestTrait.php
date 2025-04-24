<?php

namespace App\Traits;

use App\Exceptions\AppException;
use Illuminate\Support\Facades\Gate;

/** Обработка ошибок политик в исключении */
trait PolicyTestTrait
{
    /**
     * @throws AppException
     */
    private function testPolicy(string $policy, mixed $args): void
    {
        $response = Gate::inspect($policy, $args);

        if ($response->denied()) {
            throw new AppException($response->status());
        }
    }
}
