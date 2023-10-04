<?php

declare(strict_types=1);

/**
 * This file is part of datana-gmbh/fake-api-client.
 *
 * (c) Datana GmbH <info@datana.rocks>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Datana\FakeApi\Api\Formulario;

use Datana\FakeApi\Api\FakeApiClient;
use Datana\Formulario\Api\DateneingabenApiInterface;
use Datana\Formulario\Api\Domain\Value\Dateneingabe;
use Datana\Formulario\Api\Domain\Value\DateneingabeId;
use Datana\Formulario\Api\Domain\Value\DateneingabenCollection;
use Datana\Formulario\Api\Exception\DateneingabeNotFound;
use Datana\Formulario\Api\Exception\NonUniqueResult;
use OskarStark\Value\TrimmedNonEmptyString;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class DateneingabenApi implements DateneingabenApiInterface
{
    private FakeApiClient $client;
    private LoggerInterface $logger;

    public function __construct(FakeApiClient $client, ?LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger ?? new NullLogger();
    }

    public function byAktenzeichen(string $aktenzeichen): DateneingabenCollection
    {
        try {
            $response = $this->client->request(
                'GET',
                '/api/formulario/dateneingaben',
                [
                    'query' => [
                        'aktenzeichen' => TrimmedNonEmptyString::fromString(
                            $aktenzeichen,
                            '$aktenzeichen must not be an empty string',
                        )->toString(),
                    ],
                ],
            );

            $this->logger->debug('Response', $response->toArray());

            return DateneingabenCollection::fromArray($response->toArray());
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());

            throw $e;
        }
    }

    public function byId(DateneingabeId $id): Dateneingabe
    {
        try {
            $response = $this->client->request(
                'GET',
                '/api/formulario/dateneingaben',
                [
                    'query' => [
                        'id' => $id->toInt(),
                    ],
                ],
            );

            $this->logger->debug('Response', $response->toArray());

            $collection = DateneingabenCollection::fromArray($response->toArray());

            if ($collection->empty()) {
                throw DateneingabeNotFound::withDateneingabeId($id);
            }

            if ($collection->count() !== 1) {
                throw NonUniqueResult::withDateneingabeId($id);
            }

            return $collection->latest();
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());

            throw $e;
        }
    }
}
