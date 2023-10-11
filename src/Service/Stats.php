<?php

namespace Riddle\Api\Service;

require_once(__DIR__ . '/ApiService.php');

class Stats extends ApiService
{
    /**
     * Fetches stats for a given Riddle ID (works with 1.0 & 2.0 Riddles)
     * If $dateFrom and $dateTo are NULL alltime stats will be returned; please not that date from & date to must be set if $overview is set to true.
     * 
     * @return array stats for the specified Riddle
     */
    public function fetchRiddleStats(string $riddleId, ?string $dateFrom = null, ?string $dateTo = null, bool $overview = false): array
    {
        return $this->fetch('rid', $riddleId, $dateFrom, $dateTo, $overview);
    }

    /**
     * Fetches stats for a given Team ID (works with 1.0 & 2.0 Riddles)
     * If $dateFrom and $dateTo are NULL alltime stats will be returned.
     * 
     * @return array stats for the specified Riddle
     */
    public function fetchTeamStats(int $teamId, ?string $dateFrom = null, ?string $dateTo = null, bool $overview = false): array
    {
        return $this->fetch('team', $teamId, $dateFrom, $dateTo, $overview);
    }

    /**
     * Fetches stats for a given Team ID (works with 1.0 & 2.0 Riddles)
     * If $dateFrom and $dateTo are NULL alltime stats will be returned; please not that date from & date to must be set if $overview is set to true.
     * 
     * @param int $teamId the ID of the team
     * @param bool $overview if true the stats will be split up into several parts; useful for e.g. dashboard graphs
     * @return array stats for the specified Riddle
     */
    public function fetchUserStats(int $teamId, ?string $dateFrom = null, ?string $dateTo = null, bool $overview = false): array
    {
        return $this->fetch('team', $teamId, $dateFrom, $dateTo, $overview);
    }

    /**
     * Fetches stats from a given namespace (team / user / rid) from a given entity ID.
     * If $dateFrom and $dateTo are NULL alltime stats will be returned; please not that date from & date to must be set if $overview is set to true.
     * 
     * @param string $namespace team / user / rid
     * @param string|int $entityId the ID of the namespace
     * @param string|null $dateFrom the date from which to fetch stats (format: YYYY-MM-DD)
     * @param string|null $dateTo the date to which to fetch stats (format: YYYY-MM-DD)
     * @param bool $overview if true the stats will be split up into several parts; useful for e.g. dashboard graphs
     * 
     * @return array stats for the specified namespace + entity (optional: date range)
     */
    protected function fetch(string $namespace, string|int $entityId, ?string $dateFrom = null, ?string $dateTo = null, bool $overview = false): array
    {
        $params = [
            'namespace' => $namespace,
            'entityId' => $entityId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ];

        if ($overview) {
            if (null === $dateFrom || null === $dateTo) {
                throw new \Exception('Date from & date to must be set if overview is set to true.');
            }

            return $this->client->getHTTPConnector()->getArrayContent('stats/overview-fetch', [], $params, method: 'POST');
        }

        return $this->client->getHTTPConnector()->getArrayContent('stats/fetch', [], $params, method: 'POST');
    }
}