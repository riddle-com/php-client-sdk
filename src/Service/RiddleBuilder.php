<?php

namespace Riddle\Api\Service;

require_once(__DIR__ . '/ApiService.php');

class RiddleBuilder extends ApiService
{
    public const RIDDLE_BUILDER_TYPE_POLL = 'Poll';
    public const RIDDLE_BUILDER_TYPE_QUIZ = 'Quiz';

    /**
     * Builds a Riddle with a specified type (Poll / Quiz) and a build configuration.
     * 
     * @return array the serialized created Riddle
     */
    public function buildRiddle(string $type, array $build): array
    {
        return $this->client->getHTTPConnector()->getArrayContent('riddle-builder', [], [
            'type' => $type,
            'build' => $build,
        ]);
    }
}