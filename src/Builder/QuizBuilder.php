<?php

namespace Riddle\Api\Builder;

use Riddle\Api\Client;
use Riddle\Api\Exception\BuilderInvalidItemsException;
use Riddle\Api\Service\RiddleBuilder;

class QuizBuilder extends RiddleBuilderFrame
{
    public function __construct(Client $client)
    {
        parent::__construct($client, RiddleBuilder::RIDDLE_BUILDER_TYPE_QUIZ);
    }

    protected function validateQuestionItems(array $items): bool
    {
        foreach ($items as $itemQuestion => $itemIsCorrect) {
            if (!is_string($itemQuestion) || !is_bool($itemIsCorrect)) {
                throw new BuilderInvalidItemsException('Quiz question items must be an array of strings (question) and booleans (isCorrect), for example: {"A": true, "B": false}.');
            }
        }

        return true;
    }

    public function addResult(string $title, string $description, int $minPercentage, int $maxPercentage): self
    {
        $this->build['results'][] = [
            'title' => $title,
            'description' => $description,
            'minPercentage' => $minPercentage,
            'maxPercentage' => $maxPercentage,
        ];

        return $this;
    }
}