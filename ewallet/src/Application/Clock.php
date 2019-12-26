<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

class Clock
{
    /** @var DateTimeInterface|null */
    private static $now;

    public static function now(): DateTimeInterface
    {
        return self::$now ?? CarbonImmutable::now('UTC');
    }

    public static function fromFormattedString(string $formattedDate): DateTimeInterface
    {
        $dateTime = CarbonImmutable::createFromFormat('Y-m-d H:i:s', $formattedDate, new DateTimeZone('UTC'));
        if ($dateTime instanceof DateTimeInterface) {
            return $dateTime;
        }
        throw new InvalidArgumentException("$formattedDate is not valid formatted date");
    }

    public static function freezeTimeAt(DateTimeInterface $now): void
    {
        self::$now = $now;
    }

    public static function continue(): void
    {
        self::$now = null;
    }
}
