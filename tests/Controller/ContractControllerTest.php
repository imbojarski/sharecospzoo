<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Kernel;

final class ContractControllerTest extends WebTestCase
{
    /**
     * @return string
     */
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    /**
     *
     */
    public function testIndexReturnsSuccessfulResponse(): void
    {
        $this->makeRequestAndAssertResponse();
    }

    /**
     *
     */
    public function testIndexReturnsNonEmptyContractList(): void
    {
        $contracts = $this->makeRequestAndAssertResponse();

        self::assertNotEmpty($contracts, 'Contracts array should not be empty.');
    }

    /**
     *
     */
    public function testIndexHandlesEmptyContracts(): void
    {
        $contracts = $this->makeRequestAndAssertResponse();

        self::assertIsArray($contracts);
    }

    /**
     * @return array<mixed>
     */
    private function makeRequestAndAssertResponse(): array
    {
        $client = static::createClient();
        $client->request('GET', '/contract');

        $response = $client->getResponse();
        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('Content-Type', 'application/json');

        $responseContent = $response->getContent();
        $contracts = json_decode((string)$responseContent, true);

        self::assertIsArray($contracts, 'Response should be a valid JSON array.');

        return $contracts;
    }
}
