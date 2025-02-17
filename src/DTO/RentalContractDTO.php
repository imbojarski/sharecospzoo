<?php

namespace App\DTO;

use App\Entity\MonthlyRent;
use App\Entity\PaymentsTerm;
use App\Entity\RentalContract;

class RentalContractDTO
{
    private int $id;
    private int $customerId;
    private int $objectId;
    private int $objectSize;
    private int $lastPaymentTermId;
    /**
     * @var MonthlyRent[]|null
     */
    private ?array $monthlyRent = null;

    private ?array $paymentTerms = null;

    /**
     * @param RentalContract $data
     *
     * @return self
     */
    public static function fromEntity(RentalContract $data): self
    {
        return (new self())
            ->setId($data->getId())
            ->setCustomerId($data->getCustomerId())
            ->setObjectSize($data->getObjectSize())
            ->setObjectId($data->getObjectId())
            ->setLastPaymentTermId($data->getLastPaymentTermId());
    }

    /**
     * @param MonthlyRent $monthlyRent
     *
     * @return self
     */
    public function addMonthlyRent(MonthlyRent $monthlyRent): self
    {
        $this->monthlyRent[] = $monthlyRent ?? [];

        return $this;
    }

    /**
     * @return MonthlyRent[]|null
     */
    public function getMonthlyRents(): ?array
    {
        return $this->monthlyRent;
    }

    /**
     * @param PaymentsTerm $paymentsTerm
     *
     * @return self
     */
    public function addPaymentTerm(PaymentsTerm $paymentsTerm): self
    {
        $this->paymentTerms[] = $paymentsTerm ?? [];

        return $this;
    }

    /**
     * @return PaymentsTerm[]|null
     */
    public function getPaymentTerms(): ?array
    {
        return $this->paymentTerms;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

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
    public function getObjectId(): int
    {
        return $this->objectId;
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
    public function getObjectSize(): int
    {
        return $this->objectSize;
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
    public function getLastPaymentTermId(): int
    {
        return $this->lastPaymentTermId;
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
}
