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
use Datana\Formulario\Api\StatisticsApiInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Webmozart\Assert\Assert;

final class StatisticsApi implements StatisticsApiInterface
{
    private FakeApiClient $client;
    private LoggerInterface $logger;

    public function __construct(FakeApiClient $client, ?LoggerInterface $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger ?? new NullLogger();
    }

    public function numberOfCockpitInvitationMailsSent(): int
    {
        try {
            $response = $this->client->request(
                'GET',
                '/api/formulario/statistics',
            );

            $array = $response->toArray();

            $this->logger->debug('Response', $array);

            Assert::keyExists($array, 'number_of_cockpit_invitation_mails_sent');

            return $array['number_of_cockpit_invitation_mails_sent'];
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());

            throw $e;
        }
    }
}
