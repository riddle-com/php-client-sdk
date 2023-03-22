<?php

namespace Riddle\Api\Service;

require_once(__DIR__ . '/ApiService.php');

class RiddleV1 extends ApiService
{
    public function getRiddle(int $riddleId): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/riddle-v1/' . $riddleId);
    }

    public function getEmbedCode(int $riddleId): string
    {
        return $this->client->getHTTPConnector()->getStringContent('api/riddle-v1/embed-code/' . $riddleId);
    }

    public function list(?int $teamId = null, ?string $riddleType = null, array $tagIds = null, ?string $status = null, ?string $search = null, ?string $sortBy = null, ?string $sortOrder = null, ?int $page = null): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/riddle-v1/list', [
            'teamId' => $teamId,
            'type' => $riddleType,
            'tags' => $tagIds,
            'status' => $status,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $page,
    ], [], 'GET', [200, 404])['items'] ?? []; // accept 404 as well as the old API returns HTTP 404 if no riddles were found
    }

    public function getLeadFields(int $riddleId): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/riddle-v1/lead-fields', ['riddleId' => $riddleId]);
    }
}