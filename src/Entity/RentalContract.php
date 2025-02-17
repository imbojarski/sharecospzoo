<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RentalContractRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RentalContractRepository::class)]
class RentalContract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    private int $id;

    #[ORM\Column(name: 'customer_id', type: Types::INTEGER, nullable: false)]
    private int $customerId;

    #[ORM\Column(name: 'object_id', type: Types::INTEGER, nullable: false)]
    private int $objectId;

    #[ORM\Column(name: 'object_size', type: Types::INTEGER, nullable: false)]
    private int $objectSize;

    #[ORM\Column(name: 'last_payment_term_id', type: Types::INTEGER, nullable: false)]
    private int $lastPaymentTermId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $customerId
     *
     * @return self
     */
    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    /**
     * @param int $objectId
     *
     * @return self
     */
    public function setObjectId(int $objectId): self
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->objectId;
    }

    /**
     * @param int $objectSize
     *
     * @return self
     */
    public function setObjectSize(int $objectSize): self
    {
        $this->objectSize = $objectSize;

        return $this;
    }

    /**
     * @return int
     */
    public function getObjectSize(): int
    {
        return $this->objectSize;
    }

    /**
     * @param int $lastPaymentTermId
     *
     * @return self
     */
    public function setLastPaymentTermId(int $lastPaymentTermId): self
    {
        $this->lastPaymentTermId = $lastPaymentTermId;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastPaymentTermId(): int
    {
        return $this->lastPaymentTermId;
    }
}
