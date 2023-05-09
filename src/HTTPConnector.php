<?php

namespace Riddle\Api;

use Riddle\Api\Client;
use Riddle\Api\Exception\ApiJSONParseException;
use Riddle\Api\Exception\ApiRequestException;

require_once(__DIR__ . '/Response.php');
require_once(__DIR__ . '/Exception/ApiRequestException.php');

/**
 * This class manages the HTTP requests & responses from the Riddle 2.0 API.
 * It handles exceptions and also uses the API access token if one is given.
 */
class HTTPConnector
{
    private Client $client;
    private string $baseUrl;
    private string $baseApiUrl;

    public function __construct(Client $client, string $baseUrl, string $baseApiUrl)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->baseApiUrl = $baseApiUrl;
    }

    public function buildUrl(string $endpoint, array $queryParameters = [], bool $isApi = true)
    {
        $baseUrl = $isApi ? $this->baseApiUrl : $this->baseUrl;

        if (empty($queryParameters)) {
            return \sprintf('%s/%s', $baseUrl, $endpoint);
        }
        
        return \sprintf('%s/%s?%s', $baseUrl, $endpoint, \http_build_query($queryParameters));
    }

    public function getArrayContent(string $endpoint, array $queryParameters = [], array $jsonParameters = [], string $method = 'GET', array $acceptedCodes = [200], bool $isApi = true): array
    {
        return $this->request($endpoint, $queryParameters, $jsonParameters, $method, true, $acceptedCodes, $isApi)->getContent();
    }

    public function getStringContent(string $endpoint, array $queryParameters = [], array $jsonParameters = [], string $method = 'GET', array $acceptedCodes = [200]): string
    {
        return $this->request($endpoint, $queryParameters, $jsonParameters, $method, false, $acceptedCodes);
    }

    /**
     * @return string|Response returns only a string if $expectJson is set to false; a whole Response object instead if it is a JSON Request
     */
    public function request(string $endpoint, array $queryParameters = [], array $jsonParameters = [], string $method = 'GET', bool $expectJson = true, array $acceptedCodes = [200], bool $isApi = true)
    {
        $url = $this->buildUrl($endpoint, $queryParameters, $isApi);
        $ch = \curl_init();
        $curl_opt = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'riddle/api-client',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
        ];

        if (null !== $accessToken = $this->client->getAccessToken()) {
            $curl_opt[CURLOPT_HTTPHEADER][] = 'X-RIDDLE-BEARER: Bearer '.$accessToken; // add the access token to the headers for the request
        }

        if ($method === 'POST') {
            $curl_opt[CURLOPT_POST] = 1;
            $curl_opt[CURLOPT_POSTFIELDS] = \json_encode($jsonParameters);
        } else {
            $curl_opt[CURLOPT_CUSTOMREQUEST] = $method;
            $curl_opt[CURLOPT_POSTFIELDS] = \json_encode($jsonParameters);
        }

        \curl_setopt_array($ch, $curl_opt);

        $content = \curl_exec($ch);

        if (false === $content) {
            return [\curl_error($ch), \curl_errno($ch)];
        }

        $httpCode = \curl_getinfo($ch, CURLINFO_HTTP_CODE);
        \curl_close($ch);
        $jsonResponse = \json_decode($content, true);

        if (\in_array($httpCode, $acceptedCodes, true)) {
            if (404 === $httpCode) {
                return $expectJson
                    ? new Response($endpoint, $httpCode, []) // empty array as default for 404
                    : '';
            }

            // we could add more cases here if needed
        } elseif ($httpCode >= 300) {
            throw new ApiRequestException($endpoint, $httpCode, $jsonResponse ?? $content);
        }

        if (!$expectJson) {
            return $content;
        }

        if (null === $jsonResponse) {
            throw new ApiJSONParseException($endpoint, $httpCode, $content); // we expected a JSON and got something different
        } elseif (!$jsonResponse['success']) {
            throw new ApiRequestException($endpoint, $httpCode, $jsonResponse);
        }

        // already dive into the 'data' sub element - this is common for our API and we can skip this level once we investigated whether the response failed or not
        $jsonResponse = $jsonResponse['data'] ?? $jsonResponse;
        $response = new Response($endpoint, $httpCode, $jsonResponse);

        return $response;
    }
}