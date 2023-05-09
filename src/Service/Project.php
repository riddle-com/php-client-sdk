<?php

namespace Riddle\Api\Service;

require_once(__DIR__ . '/ApiService.php');

class Project extends ApiService
{
    public function list(): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('project/list')['items'] ?? [];
    }
}