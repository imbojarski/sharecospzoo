<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CurrencyRateRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRateRepository::class)]
class CurrencyRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: Types::INTEGER, nullable: false)]
    private ?int $id = null;

    #[ORM\Column(name: 'usd', type: Types::FLOAT, nullable: true)]
    private ?float $usd = null;

    #[ORM\Column(name: 'eur', type: Types::FLOAT, nullable: true)]
    private ?float $eur = null;

    #[ORM\Column(name: 'gbp', type: Types::FLOAT, nullable: true)]
    private ?float $gbp = null;

    #[ORM\Column(name: 'date', type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $date = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getUsd(): ?float
    {
        return $this->usd;
    }

    /**
     * @param float $usd
     *
     * @return static
     */
    public function setUsd(float $usd): static
    {
        $this->usd = $usd;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getEur(): ?float
    {
        return $this->eur;
    }

    /**
     * @param float $eur
     *
     * @return static
     */
    public function setEur(float $eur): static
    {
        $this->eur = $eur;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getGbp(): ?float
    {
        return $this->gbp;
    }

    /**
     * @param float $gbp
     *
     * @return static
     */
    public function setGbp(float $gbp): static
    {
        $this->gbp = $gbp;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDate(): ?DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @param DateTimeImmutable $date
     *
     * @return static
     */
    public function setDate(DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }
}
