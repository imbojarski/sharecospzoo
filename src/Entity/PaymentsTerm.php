<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PaymentsTermRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentsTermRepository::class)]
class PaymentsTerm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    private int $id;

    #[ORM\Column(name: 'start_date', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $startDate = null;

    #[ORM\Column(name: 'end_date', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $endDate = null;

    #[ORM\Column(name: 'currency_symbol', type: Types::STRING, length: 255, nullable: false)]
    private string $currencySymbol;

    #[ORM\Column(name: 'unit_price', type: Types::FLOAT, nullable: false)]
    private float $unitPrice;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param DateTimeImmutable|null $startDate
     *
     * @return self
     */
    public function setStartDate(?DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @param DateTimeImmutable|null $endDate
     *
     * @return self
     */
    public function setEndDate(?DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getEndDate(): ?DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @param string $currencySymbol
     *
     * @return self
     */
    public function setCurrencySymbol(string $currencySymbol): self
    {
        $this->currencySymbol = $currencySymbol;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrencySymbol(): string
    {
        return $this->currencySymbol;
    }

    /**
     * @param float $unitPrice
     *
     * @return self
     */
    public function setUnitPrice(float $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }
}
