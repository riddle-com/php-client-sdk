<?php

namespace Riddle\Api\Exception;

use Exception;

class ApiException extends Exception
{
    protected string $endpoint;
    protected int $httpCode;

    public function __construct(string $endpoint, int $httpCode, string $message)
    {
        $this->endpoint = $endpoint;
        $this->code = $httpCode;
        $this->message = $message;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function getHTTPCode(): int
    {
        return $this->httpCode;
    }
}