<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\PaymentsTerm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PaymentsTerm>
 */
class PaymentsTermRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentsTerm::class);
    }

    /**
     * @param int $id
     *
     * @return PaymentsTerm|null
     */
    public function getPaymentTermById(int $id): ?PaymentsTerm
    {
        return $this->find($id);
    }

    /**
     * @param PaymentsTerm $term
     *
     * @return PaymentsTerm
     */
    public function save(PaymentsTerm $term): PaymentsTerm
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($term);
        $entityManager->flush();

        return $term;
    }

}
