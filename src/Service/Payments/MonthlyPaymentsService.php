<?php

declare(strict_types=1);

namespace App\Service\Payments;

use App\Entity\MonthlyPayments;
use App\Entity\MonthlyRent;
use App\Entity\PaymentsTerm;
use App\Entity\RentalContract;
use App\Helper\DateTimeHelper;
use App\Repository\MonthlyPaymentsRepository;
use DateMalformedStringException;
use DateTimeImmutable;

readonly class MonthlyPaymentsService
{
    /**
     * @param MonthlyPaymentsRepository $monthlyPaymentsRepository
     */
    public function __construct(
        private MonthlyPaymentsRepository $monthlyPaymentsRepository,
    ) {
    }

    /**
     * @param MonthlyRent $monthlyRent
     * @param int $objectSize
     * @param float $unitPrice
     * @param bool $wholeMonth
     *
     * @return MonthlyPayments
     *
     * @throws DateMalformedStringException
     */
    public function createMonthlyPayment(
        MonthlyRent $monthlyRent,
        int $objectSize,
        float $unitPrice,
        bool $wholeMonth
    ): MonthlyPayments {
        $paymentPeriod = $monthlyRent->getPaymentPeriod();
        $dates = DateTimeHelper::getFirstOrLastDayOfMonth($paymentPeriod);
        $daysPaid = DateTimeHelper::calculateDaysBetween($dates['first'], $dates['last']);
        $paymentAmount = self::calculateTotalPaymentAmount($objectSize, $unitPrice, $daysPaid, $paymentPeriod);

        $monthlyPayment = (new MonthlyPayments())
            ->setPaymentTermsId($monthlyRent->getPaymentTermId())
            ->setMonthlyRentId($monthlyRent->getId())
            ->setPaidFrom($dates['first'])
            ->setPaidTo($dates['last'])
            ->setDaysPaid($daysPaid)
            ->setPaymentAmount($paymentAmount);

        return $this->storeMonthlyPayment($monthlyPayment);
    }

    /**
     * @param MonthlyPayments $monthlyPayments
     *
     * @return MonthlyPayments
     */
    public function storeMonthlyPayment(MonthlyPayments $monthlyPayments): MonthlyPayments
    {
        return $this->monthlyPaymentsRepository->save($monthlyPayments);
    }

    /**
     * @param MonthlyPayments $monthlyPayments
     * @param PaymentsTerm $newTerms
     * @param RentalContract $contract
     * @param MonthlyRent $monthlyRent
     *
     * @return MonthlyPayments
     *
     * @throws DateMalformedStringException
     */
    public function updateMonthlyPayment(
        MonthlyPayments $monthlyPayments,
        PaymentsTerm $newTerms,
        RentalContract $contract,
        MonthlyRent $monthlyRent
    ): MonthlyPayments {
        $currentDate = new DateTimeImmutable();
        $originalEndDate = $monthlyPayments->getPaidTo();

        $remainingDays = DateTimeHelper::calculateDaysBetween($currentDate, $originalEndDate);
        $paidDays = DateTimeHelper::calculateDaysBetween($monthlyPayments->getPaidFrom(), $currentDate);

        $newAmount = self::calculateTotalPaymentAmount(
            $contract->getObjectSize(),
            $newTerms->getUnitPrice(),
            $remainingDays,
            $monthlyRent->getPaymentPeriod()
        );

        $oldAmount = self::calculateTotalPaymentAmount(
            $contract->getObjectSize(),
            $paidDays,
            $monthlyRent->getPaymentPeriod(),
            $monthlyRent->getPaymentPeriod()
        );

        $monthlyPayments
            ->setPaidTo($currentDate)
            ->setDaysPaid($paidDays)
            ->setPaymentAmount($oldAmount);

        $newMonthlyPayment = (new MonthlyPayments())
            ->setPaidFrom($currentDate)
            ->setPaidTo($originalEndDate)
            ->setDaysPaid($remainingDays)
            ->setPaymentAmount($newAmount)
            ->setMonthlyRentId($monthlyPayments->getMonthlyRentId())
            ->setPaymentTermsId($newTerms->getId());

        $this->storeMonthlyPayment($monthlyPayments);

        return $this->storeMonthlyPayment($newMonthlyPayment);
    }

    /**
     * @param int $monthlyRentId
     *
     * @return MonthlyPayments|null
     */
    public function getLatestMonthlyPaymentByMonthlyRentId(int $monthlyRentId): ?MonthlyPayments
    {
        return $this->monthlyPaymentsRepository->getLatestMonthlyPaymentByMonthlyRentId($monthlyRentId);
    }

    /**
     * @param int $objectSize
     * @param float $unitPrice
     * @param int $days
     * @param int $month
     *
     * @return float
     *
     * @throws DateMalformedStringException
     */
    private static function calculateTotalPaymentAmount(int $objectSize, float $unitPrice, int $days, int $month): float
    {
        $daysInMonth = DateTimeHelper::getNumberOfDaysInMonth($month);

        if ($daysInMonth === 0) {
            throw new \InvalidArgumentException('Days in month cannot be zero.');
        }

        return round(($days * ($objectSize * $unitPrice)) / $daysInMonth, 2);
    }
}
