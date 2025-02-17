<?php

namespace App\Command;

use App\Service\CurrencyRate\CurrencyRateService;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'sync-currencies',
    description: 'Fetch currencies rates and store them in the database',
)]
class SyncCurrenciesCommand extends Command
{
    /**
     * @param CurrencyRateService $currencyRateService
     */
    public function __construct(private readonly CurrencyRateService $currencyRateService)
    {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Fetching currencies rates');

        try {
            $this->currencyRateService->storeDailyRates();
            $io->success('Currencies rates stored in the database successfully');
        } catch (Exception $e) {
            $io->error('Failed to store currencies rates: ' . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
