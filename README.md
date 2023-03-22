# Riddle PHP client

This library makes it easy to work with the Riddle API v3 & its webhook integration.

Available endpoints can be found [here](https://www.riddle.com/creator/v3/docs).

## Installation

### With composer

When working with composer all you need to do is run the following command:

```
composer require riddle/client
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