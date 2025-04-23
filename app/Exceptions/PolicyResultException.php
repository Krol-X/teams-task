<?php

namespace App\Exceptions;

use App\Enums\PolicyResultEnum;

class PolicyResultException extends \Exception
{
    public function __construct(int $result)
    {
        parent::__construct(PolicyResultEnum::from($result)->message(), $result);
    }
}
