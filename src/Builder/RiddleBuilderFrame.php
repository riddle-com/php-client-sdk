<?php

namespace Riddle\Api\Builder;

use Riddle\Api\Client;

require_once(__DIR__ . '/../Client.php');

/**
 * Abstract class which helps us to build Riddle type specific builder classes.
 */
abstract class RiddleBuilderFrame
{
    protected Client $client;
    protected string $type;
    protected array $build;

    public function __construct(Client $client, string $type)
    {
        $this->client = $client;
        $this->type = $type;
        $this->build = [
            'blocks' => [],
        ];
    }

    // this is to differentiate between polls & quizzes in the builder class when building questions.
    protected abstract function validateQuestionItems(array $items): bool;

    /**
     * This function sends the created build to the Riddle API and will return the serizalized Riddle.
     * 
     * @return array the serialized created Riddle
     */
    public function build(bool $publishAfterCreation = true): array
    {
        return $this->client->riddleBuilder()->buildRiddle($this->type, $this->build, $publishAfterCreation);
    }

    public function setTitle(string $title): self
    {
        $this->build['title'] = $title;

        return $this;
    }

    public function addSingleChoiceQuestion(string $title, array $items): static
    {
        $this->validateQuestionItems($items);

        return $this->addBlock($title, 'SingleChoice', $items);
    }

    public function addMultipleChoiceQuestion(string $title, array $items): static
    {
        $this->validateQuestionItems($items);

        return $this->addBlock($title, 'SingleChoice', $items);
    }

    public function getRawBuild(): array
    {
        return $this->build;
    }

    protected function addBlock(string $title, string $type, array $items): self
    {
        $this->build['blocks'][] = [
            'title' => $title,
            'type' => $type,
            'items' => $items,
        ];

        return $this;
    }
}