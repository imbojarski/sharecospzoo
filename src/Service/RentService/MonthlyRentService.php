<?php

declare(strict_types=1);

namespace App\Service\RentService;

use App\Entity\MonthlyRent;
use App\Entity\PaymentsTerm;
use App\Entity\RentalContract;
use App\Repository\MonthlyRentRepository;

/**
 * @readonly
 */
readonly class MonthlyRentService
{
    /**
     * @param MonthlyRentRepository $monthlyRentRepository
     */
    public function __construct(private MonthlyRentRepository $monthlyRentRepository)
    {
    }

    /**
     * @param RentalContract $contract
     * @param PaymentsTerm $term
     * @param int $month
     *
     * @return MonthlyRent
     */
    public function createMonthlyRent(RentalContract $contract, PaymentsTerm $term, int $month): MonthlyRent
    {
        $monthlyRent = (new MonthlyRent())
            ->setPaymentTermId($term->getId())
            ->setPaymentPeriod($month)
            ->setRentalContractId($contract->getId());

        return $this->saveMonthlyRent($monthlyRent);
    }

    /**
     * @param MonthlyRent $monthlyRent
     *
     * @return MonthlyRent
     */
    public function saveMonthlyRent(MonthlyRent $monthlyRent): MonthlyRent
    {
        return $this->monthlyRentRepository->save($monthlyRent);
    }

    /**
     * @param int $contractId
     *
     * @return MonthlyRent[]|null
     */
    public function getMonthlyRentsByContractId(int $contractId): ?array
    {
        return $this->monthlyRentRepository->findBy(['rentalContractId' => $contractId]);
    }

    /**
     * @param int $contractId
     *
     * @return MonthlyRent|null
     */
    public function getLatestActiveMonthlyRentByContractId(int $contractId): ?MonthlyRent
    {
        return $this->monthlyRentRepository
            ->findOneBy(['rentalContractId' => $contractId], ['id' => 'DESC']);
    }
}
