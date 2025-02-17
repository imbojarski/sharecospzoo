<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MonthlyRentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonthlyRentRepository::class)]
class MonthlyRent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: 'rental_contract_id', type: Types::INTEGER, nullable: false)]
    private int $rentalContractId;

    #[ORM\Column(name: 'payment_term_id', type: Types::INTEGER, nullable: false)]
    private int $paymentTermId;

    #[ORM\Column(name: 'payment_period', type: Types::INTEGER, nullable: false)]
    private int $paymentPeriod;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRentalContractId(): int
    {
        return $this->rentalContractId;
    }

    /**
     * @param int $rentalContractId
     *
     * @return self
     */
    public function setRentalContractId(int $rentalContractId): self
    {
        $this->rentalContractId = $rentalContractId;

        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentTermId(): int
    {
        return $this->paymentTermId;
    }

    /**
     * @param int $paymentTermId
     *
     * @return self
     */
    public function setPaymentTermId(int $paymentTermId): self
    {
        $this->paymentTermId = $paymentTermId;

        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentPeriod(): int
    {
        return $this->paymentPeriod;
    }

    /**
     * @param int $paymentPeriod
     *
     * @return self
     */
    public function setPaymentPeriod(int $paymentPeriod): self
    {
        $this->paymentPeriod = $paymentPeriod;

        return $this;
    }
}
