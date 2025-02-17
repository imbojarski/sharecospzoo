<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CurrencyRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CurrencyRate>
 */
class CurrencyRateRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrencyRate::class);
    }

    /**
     * @param CurrencyRate $currencyRate
     *
     * @return CurrencyRate
     */
    public function save(CurrencyRate $currencyRate): CurrencyRate
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($currencyRate);
        $entityManager->flush();

        return $currencyRate;
    }
}
