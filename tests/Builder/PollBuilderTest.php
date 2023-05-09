<?php

namespace Riddle\Api\Tests\Builder;

use PHPUnit\Framework\TestCase;
use Riddle\Api\Builder\PollBuilder;
use Riddle\Api\Client;
use Riddle\Api\Exception\BuilderInvalidItemsException;

class PollBuilderTest extends TestCase
{
    public function testBuild_valid()
    {
        $pollBuilder = $this
            ->getPollBuilder()
            ->setResult('test_title', 'test_description')
            ->setTitle('test_title')
            ->addSingleChoiceQuestion('What is your favorite color?', ['Red', 'Blue', 'Green']);

        $this->assertEquals([
            'title' => 'test_title',
            'blocks' => [
                [
                    'title' => 'What is your favorite color?',
                    'type' => 'SingleChoice',
                    'items' => [
                        'Red',
                        'Blue',
                        'Green',
                    ],
                ],
            ],
            'result' => [
                'title' => 'test_title',
                'description' => 'test_description',
            ],
        ], $pollBuilder->getRawBuild());
    }

    public function testAddSingleChoiceQuestion_notValid_notAList()
    {
        $pollBuilder = $this->getPollBuilder();

        $this->expectException(BuilderInvalidItemsException::class);
        $pollBuilder->addSingleChoiceQuestion('What is your favorite color?', ['Red' => true, 'Blue', 'Green']);
    }

    private function getPollBuilder(): PollBuilder
    {
        return new PollBuilder(new Client('test token'));
    }
}