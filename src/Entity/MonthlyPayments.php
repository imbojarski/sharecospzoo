<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MonthlyPaymentsRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MonthlyPaymentsRepository::class)]
class MonthlyPayments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $monthlyRentId = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $paymentTermsId = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $daysPaid = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $paidFrom = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $paidTo = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $paymentAmount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonthlyRentId(): ?int
    {
        return $this->monthlyRentId;
    }

    public function setMonthlyRentId(int $monthlyRentId): static
    {
        $this->monthlyRentId = $monthlyRentId;

        return $this;
    }

    public function getPaymentTermsId(): ?int
    {
        return $this->paymentTermsId;
    }

    public function setPaymentTermsId(int $paymentTermsId): static
    {
        $this->paymentTermsId = $paymentTermsId;

        return $this;
    }

    public function getDaysPaid(): ?int
    {
        return $this->daysPaid;
    }

    public function setDaysPaid(int $daysPaid): static
    {
        $this->daysPaid = $daysPaid;

        return $this;
    }

    public function getPaidFrom(): ?DateTimeImmutable
    {
        return $this->paidFrom;
    }

    public function setPaidFrom(DateTimeImmutable $paidFrom): static
    {
        $this->paidFrom = $paidFrom;

        return $this;
    }

    public function getPaidTo(): ?DateTimeImmutable
    {
        return $this->paidTo;
    }

    public function setPaidTo(DateTimeImmutable $paidTo): static
    {
        $this->paidTo = $paidTo;

        return $this;
    }

    public function getPaymentAmount(): ?float
    {
        return $this->paymentAmount;
    }

    public function setPaymentAmount(float $paymentAmount): static
    {
        $this->paymentAmount = $paymentAmount;

        return $this;
    }
}
