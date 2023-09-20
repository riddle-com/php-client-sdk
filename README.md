# Riddle PHP client

This library makes it easy to work with the Riddle API v3 & its webhook integration.

Available endpoints can be found [here](https://www.riddle.com/creator/v3/docs).

## Installation

To use this library you need to have PHP 7.4 or higher (8.0 / 8.1) installed.

### With composer

When working with composer all you need to do is run the following command:

```
composer require riddle/client-sdk
```

### Without composer

To use it without a package manager download this repository, extract it, keep the `src/` folder and include the `Client.php` file in your project:

```php
require 'riddle-client/src/Client.php';

$client = new Riddle\Api\Client('...');
```

## Authentication

When using the API you need to authenticate yourself with an access token. You can create one [in your Riddle account](https://www.riddle.com/creator/account/access-token/) (you must be logged-in to view this page).
You can then pass the token to the client:

```php
require 'riddle-client/src/Client.php';

$client = new Riddle\Api\Client('access token');
```

Now you're ready to use the API.

## Example usages

### Get all riddles

```php
require 'riddle-client/src/Client.php';

$client = new Riddle\Api\Client('access token');
$riddles = $client->riddle()->list();
```

### Get riddles from team with tags

```php
require 'riddle-client/src/Client.php';

$teamId = 123;
$tagIds = [2]; // to get tag IDs use the tag list endpoint
$client = new Riddle\Api\Client('access token');
$riddles = $client->riddle()->list($teamId, null, $tagIds);
```

### Parse webhook

```php
require 'riddle-client/src/Webhook/Webhook.php';

try {
    $webhook = new Riddle\Api\Webhook\Webhook('WveoGBv11xet392jUwPWbmEbicUn13zR');
    $payload = $webhook->parse();
    \file_put_contents(__DIR__.'/test-webhook.json', \json_encode($payload->getPayload())); // log the webhook payload

    // now work with the payload
    $resultId = $webhookResponse->getResultData()['blockId'];
    // ...
} catch (\Throwable $ex) {
    \file_put_contents(__DIR__.'/exception.txt', $ex->getMessage()); // write to a log file in case of an exception
}
```

**Note**: The webhook signature key is not required - if it's not given the signature validation will be skipped.


### Build riddles

You can also build riddles via the API.Please note that this feature is currently in-beta and is expected to be expanded in the future.

Please drop us any feedback you have on this feature via support chat on [riddle.com](https://www.riddle.com) or send us an email @ [hello@riddle.com](mailto:hello@riddle.com)!


To use this feature, create an instance of either `PollBuilder` or `QuizBuilder`:

```php
require 'riddle-client/src/Client.php';
require 'riddle-client/src/Builder/PollBuilder.php';

$client = new Riddle\Api\Client('access token');
$pollBuilder = new Riddle\Api\Builder\PollBuilder($client);
```

#### Build a poll

When building a poll you can:
- set the title
- add single choice questions
- add multiple choice questions
- set the single result the user will see at the end of the Riddle

Here's the full code snippet:

```php
require 'riddle-client/src/Client.php';
require 'riddle-client/src/Builder/PollBuilder.php';

$client = new Riddle\Api\Client('access token');
$pollBuilder = new Riddle\Api\Builder\PollBuilder($client);

// configure the poll's settings, such as questions / title / result
$pollBuilder
    ->setTitle('My Riddle Title')
    ->addSingleChoiceQuestion('What is the answer?', ['Yes', 'No', 'Maybe'])
    ->addMultipleChoiceQuestion('What are the answers?', ['Yes', 'No', 'Maybe'])
    ->setResult('Thanks for participating!', 'We will process your answers accordingly.');

// requests the API and returns the built poll
$builtPoll = $pollBuilder->build();
```

#### Build a quiz

When building a quiz you can:
- set the title
- add single choice questions
- add multiple choice questions
- add as many results as you want, each with a title, description and score range (min/max)

Here's the full code snippet:

```php
require 'riddle-client/src/Client.php';
require 'riddle-client/src/Builder/QuizBuilder.php';

$client = new Riddle\Api\Client('access token');
$quizBuilder = new Riddle\Api\Builder\QuizBuilder($client);

// configure the quizz settings, such as questions / title / results
$quizBuilder
    ->setTitle('My Riddle Title')
    ->addSingleChoiceQuestion('What is the capital of germany?', ['Berlin' => true, 'Munich' => false, 'Hamburg' => false])
    ->addMultipleChoiceQuestion('Which words can you use to say "Hello" in German?', ['Hallo' => true, 'Ciao' => false, 'Guten Tag' => true])
    ->addResult('Not so good', 'You answered most questions incorrectly.', 0, 50)
    ->addResult('Well done', 'You answered most questions correctly.', 51, 100);

// requests the API and returns the built quiz
$builtQuiz = $quizBuilder->build();
```

**Note:** Generated Riddles will be automatically published. To disable this you must pass `false` to the `build()` method:

```php
$builtQuiz = $quizBuilder->build(false);
```