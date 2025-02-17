<?php

declare(strict_types=1);

namespace App\Service\CurrencyRate;

use App\Client\NbpApiClient;
use App\Entity\CurrencyRate;
use App\Repository\CurrencyRateRepository;
use DateTimeImmutable;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;

readonly class CurrencyRateService
{
    private const array CURRENCY_CODES = ['EUR', 'USD', 'GBP'];

    public function __construct(
        private NbpApiClient $nbpApiClient,
        private CurrencyRateRepository $currencyRateRepository,
        private LoggerInterface $logger
    ) {
    }

    /***
     * @return array<string, float>
     * @throws RuntimeException
     */
    public function fetchCurrencyRates(): array
    {
        try {
            $rates = $this->nbpApiClient->getExchangeRates();

            return array_reduce($rates[0]['rates'], function ($result, $rate) {
                if (isset($rate['code'], $rate['mid'])) {
                    $result[$rate['code']] = $rate['mid'];
                }

                return $result;
            }, []);
        } catch (Exception $e) {
            $this->logger->error('Currency rate fetch error: ' . $e->getMessage());
            throw new RuntimeException('Failed to fetch currency rates: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @return void
     */
    public function storeFetchedDailyRates(): void
    {
        $response = $this->fetchCurrencyRates();
        $rates = (new CurrencyRate())
            ->setEur(self::round($response[self::CURRENCY_CODES[0]] ?? 0))
            ->setUsd(self::round($response[self::CURRENCY_CODES[1]] ?? 0))
            ->setGbp(self::round($response[self::CURRENCY_CODES[2]] ?? 0))
            ->setDate(new DateTimeImmutable());

        $this->currencyRateRepository->save($rates);
    }

    /**
     * @param float $amount
     *
     * @return float
     */
    private static function round(float $amount): float
    {
        return round($amount, 3);
    }
}
