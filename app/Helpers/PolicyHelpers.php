<?php

use Illuminate\Auth\Access\Response;

function policyResponseFrom(bool $condition, int|null $errorStatus = null): Response
{
    $response = $condition ? Response::allow() : Response::deny();
    if (!$condition && $errorStatus) {
        return $response->withStatus($errorStatus);
    }

    return $response;
}
