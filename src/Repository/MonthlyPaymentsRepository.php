<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MonthlyPayments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MonthlyPayments>
 */
class MonthlyPaymentsRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonthlyPayments::class);
    }

    /**
     * @param MonthlyPayments $monthlyPayment
     *
     * @return MonthlyPayments
     */
    public function save(MonthlyPayments $monthlyPayment): MonthlyPayments
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($monthlyPayment);
        $entityManager->flush();

        return $monthlyPayment;
    }

    /**
     * @param int $monthlyRentId
     *
     * @return MonthlyPayments|null
     */
    public function getLatestMonthlyPaymentByMonthlyRentId(int $monthlyRentId): ?MonthlyPayments
    {
        return $this->findOneBy(['monthlyRentId' => $monthlyRentId], ['id' => 'DESC']);
    }
}
