<?php

declare(strict_types=1);

namespace App\Helper;

use DateMalformedStringException;
use DateTimeImmutable;

class DateTimeHelper
{
    /**
     * @param int $month
     *
     * @return array{first: DateTimeImmutable, last: DateTimeImmutable}
     * @throws DateMalformedStringException
     */
    public static function getFirstOrLastDayOfMonth(int $month): array
    {
        self::validateMonth($month);

        $now = new DateTimeImmutable();
        $year = (int)$now->format('Y');

        $firstDayOfMonth = new DateTimeImmutable(sprintf('%04d-%02d-01 00:00:00', $year, $month));
        $lastDayOfMonth = $firstDayOfMonth->modify('last day of this month')->setTime(23, 59, 59);

        return ['first' => $firstDayOfMonth, 'last' => $lastDayOfMonth];
    }

    /**
     * @param DateTimeImmutable $startDate
     * @param DateTimeImmutable $endDate
     *
     * @return int
     */
    public static function calculateDaysBetween(DateTimeImmutable $startDate, DateTimeImmutable $endDate): int
    {
        $interval = $startDate->diff($endDate);

        return (int)$interval->days;
    }

    /**
     * @param int $month
     *
     * @return int
     * @throws DateMalformedStringException
     */
    public static function getNumberOfDaysInMonth(int $month): int
    {
        self::validateMonth($month);

        $year = (new DateTimeImmutable())->format('Y');
        $firstDayOfMonth = new DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month));

        return (int)$firstDayOfMonth->format('t');
    }

    /**
     * Validates the month parameter.
     *
     * @param int $month
     * @throws DateMalformedStringException
     */
    private static function validateMonth(int $month): void
    {
        if ($month < 1 || $month > 12) {
            throw new DateMalformedStringException('Invalid month provided: ' . $month);
        }
    }
}
