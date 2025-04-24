<?php

namespace App\Exceptions;

use App\Enums\AppExceptionsEnum;

class AppException extends \Exception
{
    public function __construct(int $result)
    {
        parent::__construct(AppExceptionsEnum::from($result)->message(), $result);
    }
}
