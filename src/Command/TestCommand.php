<?php

namespace App\Command;

use App\Client\NbpApiClient;
use App\Entity\MonthlyRent;
use App\Entity\PaymentsTerm;
use App\Entity\RentalContract;
use App\Repository\MonthlyPaymentsRepository;
use App\Repository\MonthlyRentRepository;
use App\Repository\PaymentsTermRepository;
use App\Repository\RentalContractRepository;
use App\Service\CurrencyRate\CurrencyRateService;
use App\Service\Payments\MonthlyPaymentsService;
use App\Service\RentService\PaymentService;
use App\Service\RentService\RentService;
use DateTimeImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use function Symfony\Component\String\s;

#[AsCommand(
    name: 'test',
    description: 'Add a short description for your command',
)]
class TestCommand extends Command
{
    public function __construct(
        private readonly RentService $rentService,
        private readonly RentalContractRepository $contractRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @throws \DateMalformedStringException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $contract = $this->rentService->createRent(123, 11, 1299, 9.1, 'PLN');
        $newTerms = $this->rentService->createPaymentTerm(122, 'USD', new DateTimeImmutable());
        $this->rentService->assignNewPaymentTermToRentalContract($newTerms, $contract);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
