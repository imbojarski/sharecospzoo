<?php

namespace App\Tests\Command;

use App\Command\SyncCurrenciesCommand;
use App\Service\CurrencyRate\CurrencyRateService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Exception;

class SyncCurrenciesCommandTest extends TestCase
{
    public function testExecuteSuccess(): void
    {
        $currencyRateServiceMock = $this->createMock(CurrencyRateService::class);
        $currencyRateServiceMock->expects($this->once())
            ->method('storeFetchedDailyRates');

        $command = new SyncCurrenciesCommand($currencyRateServiceMock);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertSame(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('Currencies rates stored in the database successfully', $commandTester->getDisplay());
    }

    public function testExecuteFailure(): void
    {
        $currencyRateServiceMock = $this->createMock(CurrencyRateService::class);
        $currencyRateServiceMock->expects($this->once())
            ->method('storeFetchedDailyRates')
            ->willThrowException(new Exception('Test Exception'));

        $command = new SyncCurrenciesCommand($currencyRateServiceMock);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $this->assertSame(Command::FAILURE, $commandTester->getStatusCode());
        $this->assertStringContainsString('Failed to store currencies rates: Test Exception', $commandTester->getDisplay());
    }

    public function testConstructor(): void
    {
        $currencyRateServiceMock = $this->createMock(CurrencyRateService::class);
        $command = new SyncCurrenciesCommand($currencyRateServiceMock);

        $this->assertInstanceOf(SyncCurrenciesCommand::class, $command);
    }
}
