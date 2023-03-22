<?php

namespace Riddle\Api\Service;

require_once(__DIR__ . '/ApiService.php');

class Riddle extends ApiService
{
    public const RIDDLE_TYPE_POLL = 'Poll';
    public const RIDDLE_TYPE_QUIZ = 'Quiz';
    public const RIDDLE_TYPE_FORM = 'Form';

    public const RIDDLE_STATUS_PUBLISH = 'published';
    public const RIDDLE_STATUS_MODIFIED = 'modified';
    public const RIDDLE_STATUS_DRAFT = 'draft';

    public const RIDDLE_SEARCHORDER_CREATED = 'created';
    public const RIDDLE_SEARCHORDER_MODIFIED = 'modified';
    public const RIDDLE_SEARCHORDER_PUBLISHED = 'published';

    /**
     * Get information about a single Riddle such as the cover image, publish dates and much more.
     * 
     * @return array the serialized Riddle
     */
    public function getRiddle(string $riddleUUID): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/riddle/' . $riddleUUID);
    }

    /**
     * Get the embed code for a Riddle.
     * It is advised to cache this HTML as loading it every time from this API is quite wasteful (the embed code usually does not change unless someone changed settings in the Creator).
     * 
     * @return string the embed code HTML
     */
    public function getEmbedCode(string $riddleUUID): string
    {
        return $this->client->getHTTPConnector()->getStringContent('api/riddle/embed-code/' . $riddleUUID);
    }

    /**
     * Get a list of all riddles.
     * 
     * @param int|null $teamId only available if it is a personal account token; this way the API user can access Riddles from different teams
     * @param string|null $riddleType if given only Riddles of the specified type will be returned (Available: Poll, Quiz, Form)
     * @param array|null $tagIds an integer array of tag IDs you want to include in the list
     * @param string|null $status the status of the Riddles (Available: published, modified, draft)
     * @param string|null $search with this parameter you can filter the Riddles by the title or UUID
     * @param string|null $sortBy by which field to sort (Default: created, Available: created, modified, published)
     * @param string|null $sortOrder ASC / DESC (Default: DESC)
     * @param int|null which page to return; one page consists of 10 riddles.
     * 
     * @return array array of serialized Riddles
     */
    public function list(?int $teamId = null, ?string $riddleType = null, array $tagIds = null, ?string $status = null, ?string $search = null, ?string $sortBy = null, ?string $sortOrder = null, ?int $page = null): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/riddle/list', [], [
            'team' => $teamId,
            'type' => $riddleType,
            'tags' => $tagIds,
            'status' => $status,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'page' => $page,
        ], 'POST');
    }

    public function publish(string $riddleUUID): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/riddle/publish'.$riddleUUID, [], [], 'POST');
    }

    public function unpublish(string $riddleUUID): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/riddle/unpublish'.$riddleUUID, [], [], 'POST');
    }

    public function rename(string $riddleUUID, string $newTitle)
    {
        return $this->client->getHTTPConnector()->getArrayContent('api/riddle/rename'.$riddleUUID, [], [
            'title' => $newTitle,
        ], 'POST');
    }
}