<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\RentalContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RentalContract>
 */
class RentalContractRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RentalContract::class);
    }

    /**
     * @param RentalContract $contract
     *
     * @return RentalContract
     */
    public function save(RentalContract $contract): RentalContract
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($contract);
        $entityManager->flush();

        return $contract;
    }
}
