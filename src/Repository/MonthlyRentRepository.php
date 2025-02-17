<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MonthlyRent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MonthlyRent>
 */
class MonthlyRentRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MonthlyRent::class);
    }

    /**
     * @param MonthlyRent $monthlyRent
     *
     * @return MonthlyRent
     */
    public function save(MonthlyRent $monthlyRent): MonthlyRent
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($monthlyRent);
        $entityManager->flush();

        return $monthlyRent;
    }
}
