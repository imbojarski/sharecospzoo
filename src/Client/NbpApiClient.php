<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Psr\Log\LoggerInterface;

readonly class NbpApiClient
{
    private Client $client;

    /**
     * @param LoggerInterface $logger
     * @param string $baseUri
     * @param float $timeout
     */
    public function __construct(
        private LoggerInterface $logger,
        string $baseUri = 'http://api.nbp.pl',
        float $timeout = 10.0
    ) {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => $timeout,
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExchangeRates(): ?array
    {
        try {
            $response = $this->client->request('GET', '/api/exchangerates/tables/A', [
                'query' => ['format' => 'json'],
            ]);

            if ($response->getStatusCode() !== 200) {
                return null;
            }

            return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (GuzzleException|JsonException $e) {
            $this->logger->error($e->getMessage());

            return null;
        }
    }
}
