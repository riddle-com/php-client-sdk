<?php

namespace Riddle\Api\Builder;

use Riddle\Api\Builder\Objects\ResultPage;
use Riddle\Api\Client;
use Riddle\Api\Exception\BuilderInvalidItemsException;
use Riddle\Api\Service\RiddleBuilder;

require_once(__DIR__ . '/RiddleBuilderFrame.php');
require_once(__DIR__ . '/Objects/ResultPage.php');
require_once(__DIR__ . '/../Service/RiddleBuilder.php');
require_once(__DIR__ . '/../Exception/BuilderInvalidItemsException.php');

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

    public function addSingleChoiceQuestion(string $title, array $items, ?string $explanationTitle = null, ?int $score = null, ?string $mediaImageUrl = null, ?string $explanationDescription = null, ?string $wrongExplanationTitle = null, ?string $wrongExplanationDescription = null): static
    {
        $questionConfig = [
            'items' => $items,
        ];
        $hasExplanation = $explanationTitle !== null && $explanationDescription !== null;

        if ($hasExplanation) {
            $questionConfig['explanation'] = [
                'title' => $explanationTitle,
                'description' => $explanationDescription,
            ];
        }

        if ($wrongExplanationTitle !== null && $wrongExplanationDescription !== null) {
            if (!$hasExplanation) {
                throw new \InvalidArgumentException('You need to provide a default/correct explanation first when providing a wrong explanation.');
            }

            $questionConfig['wrongExplanation'] = [
                'title' => $wrongExplanationTitle,
                'description' => $wrongExplanationDescription,
            ];
        }

        if ($score !== null) {
            $questionConfig['score'] = $score;
        }

        if ($mediaImageUrl !== null) {
            $questionConfig['media'] = $mediaImageUrl;
        }

        return $this->addBlock($title, 'SingleChoice', $questionConfig);
    }

    public function addTypeAnswerQuestion(string $title, array $answers): static
    {
        return $this->addBlock($title, 'TextEntry', ['answers' => $answers]);
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

    public function addResultPage(ResultPage $page, int $minPercentage, int $maxPercentage): self
    {
        $this->build['results'][] = [
            'blocks' => $page->getBlocks(),
            'minPercentage' => $minPercentage,
            'maxPercentage' => $maxPercentage,
        ];

        return $this;
    }
}