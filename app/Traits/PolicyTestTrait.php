<?php

namespace App\Traits;

use App\Exceptions\PolicyResultException;
use Illuminate\Support\Facades\Gate;

/** Обработка ошибок политик в исключении */
trait PolicyTestTrait
{
    /**
     * @throws PolicyResultException
     */
    private function testPolicy(string $policy, $class): void
    {
        $canCreate = Gate::inspect($policy, $class);

        if ($canCreate->denied()) {
            throw new PolicyResultException($canCreate->status());
        }
    }
}
