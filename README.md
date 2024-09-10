# Riddle PHP client

This library makes it easy to work with...
- the Riddle API v3
- incoming Riddle webhooks (validates & parses them)

Available endpoints can be found [here](https://www.riddle.com/creator/v3/docs).

## Installation

To use this library you need to have PHP 7.4 or higher (8.0 / 8.1) installed.

### With composer

When working with composer all you need to do is run the following command:

```
composer require riddle/client-sdk
```

In your .php file you can then include the composer autoloader:

```php
require 'vendor/autoload.php';

// [... your code, e.g. fetching riddles / stats / ...]
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
require 'riddle-client/src/Service/Riddle.php';

$client = new Riddle\Api\Client('access token');
$riddles = $client->riddle()->list();
```

### Get alltime stats for a Riddle (only available for Business & Enterprise plans)

```php
require 'riddle-client/src/Client.php';
require 'riddle-client/src/Service/Stats.php';

$client = new Riddle\Api\Client('access token');
$riddleStats = $client->stats()->fetchRiddleStats('[RIDDLE_ID]');
```

### Get riddles from team with tags

```php
require 'riddle-client/src/Client.php';
require 'riddle-client/src/Service/Riddle.php';

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


### Build basic Riddles

You can also build Riddles via the API.Please note that this feature is currently in-beta and is expected to be expanded in the future.

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

### Building Riddles with custom result pages (buttons, answered blocks etc)

```php
require 'riddle-client/src/Client.php';
require 'riddle-client/src/Builder/PollBuilder.php';
require 'riddle-client/src/Builder/QuizBuilder.php';

$client = new Riddle\Api\Client('access token');

// custom result page via the ResultPage class
$resultPage = (new Riddle\Api\Builder\Objects\ResultPage())
    ->addTextBlock('Thank you for participating in our poll!')
    ->addAnsweredBlocks()
    ->addMedia('MEDIA_RUL')
    ->addSocialShare('Share this poll with your friends!', 'Check out this poll', 'This is a poll about colors')
    ->addButton('Go to our website', 'https://www.riddle.com', true)
;

// Adding it to a quiz
$quizBuilder = new (new Riddle\Api\Builder\QuizBuilder($client))
    // - >... // add other questions, form fields, etc
    ->addResultPage($resultPage, minPercentage: 0, maxPercentage: 100);

// Adding it to a poll (only one result page possible)
$pollBuilder = new (new Riddle\Api\Builder\PollBuilder($client))
    // - >... // add other questions, form fields, etc
    ->setResultPage($resultPage);
```

### Build Riddles with forms

You can add "Make a form" blocks to your Riddle. This allows you to collect user data (name, email, phone, etc).

```php
require 'riddle-client/src/Client.php';
require 'riddle-client/src/Builder/QuizBuilder.php';

$client = new Riddle\Api\Client('access token');

// custom form field builder via the FormFieldBuilder class
$formBuilder = (new Riddle\Api\Builder\Objects\FormFieldBuilder())
    ->setTitle('Contact us')
    ->addNameField('My name field')
    ->addEmailField('My email field')
    ->addPhoneField('My phone field')
    ->addUrlField('My URL field')
    ->addNumberField('My number field')
    ->addCountryField('My country field')
    ->addShortTextField('My short text field')
    ->addLongTextField('My long text field');

// Adding it to any builder (poll, quiz, etc)
$quizBuilder = new (new Riddle\Api\Builder\QuizBuilder($client))
    // - >... // add other questions, form fields, etc
    ->addFormBuilder($formBuilder);
```


### Manipulate the build directly

We are aware that the current builder classes in this SDK do not cover all possibilities of the builder API (there are just too many!).

Therefore, you can also manipulate the build directly:

```php
$quizBuilder = new (new Riddle\Api\Builder\QuizBuilder($client));

$rawBuild = $quizBuilder->getRawBuild();
$rawBuild['publish']['isShowcaseEnabled'] = false; // advanced option to disable Riddle showcase (only available embedded on customer page)

$quizBuilder->setRawBuild($rawBuild);
```