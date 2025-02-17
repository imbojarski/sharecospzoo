<?php

declare(strict_types=1);

namespace App\Service\RentService;

use App\DTO\RentalContractDTO;
use App\Entity\PaymentsTerm;
use App\Entity\RentalContract;
use App\Repository\PaymentsTermRepository;
use App\Repository\RentalContractRepository;
use App\Service\Payments\MonthlyPaymentsService;
use App\Service\RentService\MonthlyRentService;
use DateMalformedStringException;
use DateTimeImmutable;
use http\Env\Response;
use RuntimeException;

readonly class RentService
{
    public function __construct(
        private PaymentsTermRepository $paymentsTermRepository,
        private RentalContractRepository $contractRepository,
        private MonthlyRentService $monthlyRentService,
        private MonthlyPaymentsService $monthlyPaymentsService
    ) {}

    /**
     * @param int $customerId
     * @param int $objectId
     * @param int $objectSize
     * @param float $unitPrice
     * @param string $currencySymbol
     *
     * @return RentalContract
     * @throws DateMalformedStringException
     */
    public function createRent(
        int $customerId,
        int $objectId,
        int $objectSize,
        float $unitPrice,
        string $currencySymbol
    ): RentalContract {
        $paymentTerm = $this->createPaymentTerm($unitPrice, $currencySymbol, new DateTimeImmutable());
        $rentalContract = $this->createRentalContract($customerId, $objectId, $objectSize, $paymentTerm);

        $this->initializeMonthlyRent($rentalContract, $paymentTerm);

        return $rentalContract;
    }

    /**
     * @param PaymentsTerm $term
     *
     * @return PaymentsTerm
     */
    public function savePaymentsTerm(PaymentsTerm $term): PaymentsTerm
    {
        return $this->paymentsTermRepository->save($term);
    }

    /**
     * @param float $unitPrice
     * @param string $currencySymbol
     * @param DateTimeImmutable $startDate
     *
     * @return PaymentsTerm
     */
    public function createPaymentTerm(
        float $unitPrice,
        string $currencySymbol,
        DateTimeImmutable $startDate
    ): PaymentsTerm {
        $paymentTerm = (new PaymentsTerm())
            ->setStartDate($startDate)
            ->setCurrencySymbol($currencySymbol)
            ->setUnitPrice($unitPrice);

        return $this->savePaymentsTerm($paymentTerm);
    }

    /**
     * @param int $customerId
     * @param int $objectId
     * @param int $objectSize
     * @param PaymentsTerm $term
     *
     * @return RentalContract
     */
    private function createRentalContract(
        int $customerId,
        int $objectId,
        int $objectSize,
        PaymentsTerm $term
    ): RentalContract {
        $rentalContract = (new RentalContract())
            ->setObjectId($objectId)
            ->setObjectSize($objectSize)
            ->setCustomerId($customerId)
            ->setLastPaymentTermId($term->getId());

        return $this->saveRentalContract($rentalContract);
    }

    /**
     * @param RentalContract $contract
     * @param PaymentsTerm $term
     *
     * @return void
     */
    private function initializeMonthlyRent(RentalContract $contract, PaymentsTerm $term): void
    {
        $currentMonth = (new DateTimeImmutable())->format("m");
        $monthlyRent = $this->monthlyRentService->createMonthlyRent($contract, $term, (int)$currentMonth);
        $this->monthlyPaymentsService->createMonthlyPayment(
            $monthlyRent,
            $contract->getObjectSize(),
            $term->getUnitPrice(),
            true
        );
    }

    /**
     * @param RentalContract $contract
     *
     * @return RentalContract
     */
    public function saveRentalContract(RentalContract $contract): RentalContract
    {
        return $this->contractRepository->save($contract);
    }

    /**
     * @param int $id
     *
     * @return RentalContractDTO
     */
    public function getRentalContract(int $id): RentalContractDTO
    {
        $rentalContractEntity = $this->contractRepository->findOneBy(['id' => $id]);
        if (is_null($rentalContractEntity)) {
            throw new RuntimeException('Contract not found');
        }
        $rentalContractDTO = RentalContractDTO::fromEntity($rentalContractEntity);

        $monthlyRents = $this->monthlyRentService->getMonthlyRentsByContractId($id);
        foreach ($monthlyRents as $monthlyRent) {
            $rentalContractDTO->addMonthlyRent($monthlyRent);
        }

        return $rentalContractDTO;
    }

    /**
     * @param PaymentsTerm $newPaymentTerm
     * @param RentalContract $contract
     *
     * @return RentalContract
     * @throws DateMalformedStringException
     */
    public function assignNewPaymentTermToRentalContract(
        PaymentsTerm $newPaymentTerm,
        RentalContract $contract
    ): RentalContract {
        $this->savePaymentsTerm($newPaymentTerm);
        $contract->setLastPaymentTermId($newPaymentTerm->getId());
        $this->saveRentalContract($contract);

        $monthlyRent = $this->monthlyRentService->getLatestActiveMonthlyRentByContractId($contract->getId());

        if ($monthlyRent !== null) {
            $monthlyRent->setPaymentTermId($newPaymentTerm->getId());
            $this->monthlyRentService->saveMonthlyRent($monthlyRent);

            $currentPayment = $this->monthlyPaymentsService->getLatestMonthlyPaymentByMonthlyRentId(
                $monthlyRent->getId()
            );

            if ($currentPayment !== null) {
                $this->monthlyPaymentsService->updateMonthlyPayment(
                    $currentPayment,
                    $newPaymentTerm,
                    $contract,
                    $monthlyRent
                );
            }
        }

        return $contract;
    }
}
