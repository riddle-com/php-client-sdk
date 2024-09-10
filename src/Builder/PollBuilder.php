<?php

namespace Riddle\Api\Builder;

use Riddle\Api\Builder\Objects\ResultPage;
use Riddle\Api\Client;
use Riddle\Api\Exception\BuilderInvalidItemsException;
use Riddle\Api\Service\RiddleBuilder;

require_once(__DIR__ . '/../Service/RiddleBuilder.php');
require_once(__DIR__ . '/RiddleBuilderFrame.php');
require_once(__DIR__ . '/Objects/ResultPage.php');

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

    public function addSingleChoiceQuestion(string $title, array $items): static
    {
        $this->validateQuestionItems($items);

        return $this->addBlock($title, 'SingleChoice', $items);
    }
    /**
     * To set a simple resuls page with title and description.
     */
    public function setResult(string $title, string $description): static
    {
        $this->build['result'] = [
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    /**
     * With the ResultPage object you can create more complex + engaging result pages via API.
     */
    public function setResultPage(ResultPage $resultPage): static
    {
        $this->build['result']['blocks'] = $resultPage->getBlocks();

        return $this;
    }
}