<?php

namespace Riddle\Api\Builder;

use Riddle\Api\Client;
use Riddle\Api\Exception\BuilderInvalidItemsException;
use Riddle\Api\Service\RiddleBuilder;

class PollBuilder extends RiddleBuilderFrame
{
    public function __construct(Client $client)
    {
        parent::__construct($client, RiddleBuilder::RIDDLE_BUILDER_TYPE_POLL);
    }

    protected function validateQuestionItems(array $items): bool
    {
        if (\count(\array_filter(\array_keys($items), 'is_string')) > 0) {
            throw new BuilderInvalidItemsException('Poll question items must be an array of strings (question), for example: ["A", "B"].');
        }

        return true;
    }

    public function setResult(string $title, string $description): self
    {
        $this->build['result'] = [
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }
}