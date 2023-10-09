# fake-api-client

| Branch    | PHP                                         |
|-----------|---------------------------------------------|
| `master`  | [![PHP][build-status-master-php]][actions]  |

## Usage

### Installation

```bash
composer require datana-gmbh/fake-api-client
```

### Setup

```php
use Datana\FakeApi\FakeApiClient;

$baseUri = 'https://api.fake-api...';
$username = '...';
$password = '...';
$disableCache = true; // optional
$timeout = 10; // optional

$client = new FakeApiClient($baseUri, $username, $password, $disableCache, $timeout);

// you can now request any endpoint which needs authentication
$client->request('GET', '/api/something', $options);
```

## Dateneingaben

In your code you should type-hint to `Datana\Formulario\Api\DateneingabenApiInterface`

### Get by Aktenzeichen (`string`)

```php
use Datana\FakeApi\DateneingabenApi;
use Datana\FakeApi\FakeClient;
use Datana\Formulario\Api\Domain\Value\DateneingabenId;

$client = new FakeClient(/* ... */);

$api = new DateneingabenApi($client);
$response = $api->byAktenzeichen('1abcde-1234-5678-Mustermann');

/*
 * to get the DateneingabenId transform the response to array
 * and use the 'id' key.
 */
$akten = $response->toArray();
$dateneingabenId = DateneingabenId::fromInt($akte['id']);
```

### Get by ID (`Datana\Formulario\Api\Domain\Value\DateneingabenId`)

```php
use Datana\FakeApi\DateneingabenApi;
use Datana\FakeApi\FakeClient;
use Datana\Formulario\Api\Domain\Value\DateneingabenId;

$client = new FakeClient(/* ... */);

$api = new DateneingabenApi($client);

$id = DateneingabenId::fromInt(123);

$api->getById($id);
```

## Statistics

In your code you should type-hint to `Datana\Formulario\Api\StatisticsApiInterface`

### Get number of invitation mails sent for Mandantencockpit

```php
use Datana\FakeApi\StatisticsApi;
use Datana\FakeApi\FakeClient;

$client = new FakeClient(/* ... */);

$api = new StatisticsApi($client);

$api->numberOfCockpitInvitationMailsSent(); // 42
```

[build-status-master-php]: https://github.com/datana-gmbh/fake-api-client/workflows/PHP/badge.svg?branch=master

[actions]: https://github.com/datana-gmbh/fake-api-client/actions
