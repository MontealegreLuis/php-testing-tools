<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application;

use Carbon\Carbon;
use DateTime;
use DateTimeZone;

class Clock
{
    /** @var DateTime */
    private static $now;

    public static function now(): DateTime
    {
        return self::$now ?? Carbon::now('UTC')->toDate();
    }

    public static function fromFormattedString(string $formattedDate): DateTime
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $formattedDate, new DateTimeZone('UTC'))->toDate();
    }

    public static function freezeTimeAt(DateTime $now): void
    {
        self::$now = $now;
    }

    public static function continue(): void
    {
        self::$now = null;
    }
}
