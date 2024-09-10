<?php

namespace Riddle\Api\Builder;

use Riddle\Api\Builder\Objects\FormFieldBuilder;
use Riddle\Api\Client;

require_once (__DIR__ . '/../Client.php');
require_once (__DIR__ . '/Objects/ResultPage.php');
require_once (__DIR__ . '/Objects/FormFieldBuilder.php');

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

    public function setTitle(string $title): static
    {
        $this->build['title'] = $title;

        return $this;
    }

    public function addMultipleChoiceQuestion(string $title, array $items): static
    {
        $this->validateQuestionItems($items);

        return $this->addBlock($title, 'MultipleChoice', ['items' => $items]);
    }

    public function addFormBuilder(FormFieldBuilder $formBuilder): static
    {
        return $this->addBlock($formBuilder->getTitle(), 'FormFieldBuilder', ['fields' => $formBuilder->getFields()]);
    }

    /**
     * Inserts a form into the Riddle; must be in same personal project or custom project.
     */
    public function insertForm(string $formUUID): static
    {
        $this->build['blocks'][] = [
            'type' => 'FormSelect',
            'form' => $formUUID,
        ];

        return $this;
    }

    public function getRawBuild(): array
    {
        return $this->build;
    }

    public function setRawBuild(array $build): static
    {
        $this->build = $build;

        return $this;
    }

    protected function addItemsBlock(string $title, string $type, array $items): static
    {
        return $this->addBlock($title, $type, ['items' => $items]);
    }

    protected function addBlock(string $title, string $type, array $properties): static
    {
        $this->build['blocks'][] = [
            'title' => $title,
            'type' => $type,
            ...$properties,
        ];

        return $this;
    }
}