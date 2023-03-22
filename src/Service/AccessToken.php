<?php

namespace Riddle\Api\Service;

require_once(__DIR__ . '/ApiService.php');

class AccessToken extends ApiService
{
    /**
     * Revokes the access token.
     * After that the token is no longer usable and a new one should be issued.
     * Use this method when the user e.g. wants to reconnect - old access tokens always impose a security risk.
     */
    public function revoke(): bool
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/access-token/revoke', [], [], 'DELETE')['revoked'];
    }
}