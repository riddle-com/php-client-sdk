<?php

namespace Riddle\Api\Exception;

class ApiJSONParseException extends ApiException
{
    private $responseContent;

    public function __construct(string $endpoint, int $httpCode, $responseContent)
    {
        $this->responseContent = $responseContent;
        $message = \sprintf('Excepted json response, got: %s (Endpoint: %s).', \substr($responseContent, 0, 1000), $endpoint);

        parent::__construct($endpoint, $httpCode, $message);
    }

    /**
     * @return array|string
     */
    public function getResponseContent()
    {
        return $this->responseContent;
    }
}