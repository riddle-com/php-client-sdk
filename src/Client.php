<?php

namespace Riddle\Api;

use Riddle\Api\Service\AccessToken;
use Riddle\Api\Service\Oauth;
use Riddle\Api\Service\Ping;
use Riddle\Api\Service\Project;
use Riddle\Api\Service\Riddle;
use Riddle\Api\Service\RiddleV1;

require_once(__DIR__ . '/HTTPConnector.php');

class Client
{
    public const DEFAULT_BASE_URL = 'https://www.riddle.com/creator/v2';

    private string $accessToken;
    private HTTPConnector $httpConnector;

    public function __construct(?string $accessToken = null, ?string $baseUrl = null)
    {
        $this->accessToken = $accessToken;
        $this->httpConnector = new HTTPConnector($this, $baseUrl ?? self::DEFAULT_BASE_URL);
    }

    /**
     * @return bool true if the ping was successful; false or throws an exception otherwise
     */
    public function ping(): bool
    {
        return (new Ping($this))->ping();
    }

    public function oauth(): Oauth
    {
        return new Oauth($this);
    }

    public function riddle(): Riddle
    {
        return new Riddle($this);
    }

    public function riddleV1(): RiddleV1
    {
        return new RiddleV1($this);
    }

    public function project(): Project
    {
        return new Project($this);
    }

    public function accessToken(): AccessToken
    {
        return new AccessToken($this);
    }

    /**
     * @return string|null The access token as a string if it is set; null otherwise (=> not authorized)
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getHTTPConnector(): HTTPConnector
    {
        return $this->httpConnector;
    }
}