<?php

namespace Riddle\Api\Exception;

require_once(__DIR__ . '/ApiException.php');

class ApiRequestException extends ApiException
{
    protected string $endpoint;
    protected $responseContent;

    public function __construct(string $endpoint, int $httpCode, $responseContent)
    {
        $this->responseContent = $responseContent;

        if (\is_array($responseContent)) {
            $message = \sprintf('Request failed: HTTP %d: %s: %s (Endpoint: %s).', $httpCode, $responseContent['error'] ?? '/', $responseContent['message'], $endpoint);
        } else {
            $message = \sprintf('API returned HTTP %d: %s (Endpoint: %s).', $httpCode, \substr($responseContent, 0, 100), $endpoint);
        }

        parent::__construct($endpoint, $httpCode, $message);
    }

    public function getHTTPCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @return array|string
     */
    public function getResponseContent()
    {
        return $this->responseContent;
    }
}