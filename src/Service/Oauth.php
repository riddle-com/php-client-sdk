<?php

namespace Riddle\Api\Service;

use Riddle\Api\Exception\ApiRequestException;

require_once(__DIR__ . '/ApiService.php');

class Oauth extends ApiService
{
    /**
     * With this link you can generate AccessTokens.
     * Please note that the returned tokens can only read from the chosen account and cannot edit/delete anything.
     *
     * @return string the auth URL a user can use to authenticate with Riddle using OAuth 2
     */
    public function getAuthUrl(string $callbackUrl, ?string $state = null): string
    {
        return $this->client->getHTTPConnector()->buildUrl('oauth/authorize', [
            'client_id' => '',
            'redirect_uri' => $callbackUrl,
            'state' => $state,
            'type'=> 'wordpress',
        ]);
    }

    /**
     * Fetches the access token from an OAuth callback code.
     * You can get the required code from first calling the auth URL and then fetching it in the specified callback.
     * 
     * @throws ApiRequestException If a code is used more than once or is invalid
     * @return string the generated access token
     */
    public function fetchAccessToken(string $code): string
    {
        return $this->client->getHTTPConnector()->getArrayContent('oauth/access-token', [], [
            'code' => $code,
        ], 'POST')['accessToken'];
    }
}