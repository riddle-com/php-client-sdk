<?php

namespace Riddle\Api\Service;

require_once(__DIR__ . '/ApiService.php');

class Ping extends ApiService
{
    public function ping(): bool
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/ping') === 'pong';
    }
}