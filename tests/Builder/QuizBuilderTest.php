<?php

namespace Riddle\Api\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Riddle\Api\Builder\PollBuilder;
use Riddle\Api\Builder\QuizBuilder;
use Riddle\Api\Client;
use Riddle\Api\Exception\BuilderInvalidItemsException;

class QuizBuilderTest extends TestCase
{
    public function testBuild_valid()
    {
        $pollBuilder = $this
            ->getQuizBuilder()
            ->addResult('result1', 'test_description', 0, 50)
            ->addResult('result2', 'test_description', 51, 100)
            ->setTitle('test_title')
            ->addSingleChoiceQuestion('What is the capital of germany?', ['Berlin' => true, 'Lissabon' => false]);

        $this->assertEquals([
            'title' => 'test_title',
            'blocks' => [
                [
                    'title' => 'What is the capital of germany?',
                    'type' => 'SingleChoice',
                    'items' => [
                        'Berlin' => true,
                        'Lissabon' => false,
                    ],
                ],
            ],
            'results' => [
                [
                    'title' => 'result1',
                    'description' => 'test_description',
                    'minPercentage' => 0,
                    'maxPercentage' => 50,
                ],
                [
                    'title' => 'result2',
                    'description' => 'test_description',
                    'minPercentage' => 51,
                    'maxPercentage' => 100,
                ],
            ],
        ], $pollBuilder->getRawBuild());
    }

    public function testAddSingleChoiceQuestion_notValid_notAnObject()
    {
        $pollBuilder = $this->getQuizBuilder();

        $this->expectException(BuilderInvalidItemsException::class);
        $pollBuilder->addSingleChoiceQuestion('What is your favorite color?', ['Red', 'Blue', 'Green']);
    }

    private function getQuizBuilder(): QuizBuilder
    {
        return new QuizBuilder(new Client('test token'));
    }
}