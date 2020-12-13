<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders;

use DateTime;
use Faker\Factory;
use Faker\Generator;

final class Random
{
    private static ?Generator $faker = null;

    public static function date(): DateTime
    {
        return self::faker()->dateTimeThisMonth;
    }

    public static function numericId(): int
    {
        return self::faker()->numberBetween(1, 10_000);
    }

    public static function word(): string
    {
        return self::faker()->word;
    }

    public static function dollars(): float
    {
        return self::faker()->randomFloat(2, 1, 10_000);
    }

    public static function cents(): int
    {
        return self::faker()->numberBetween(1, 10_000);
    }

    public static function uuid(): string
    {
        return self::faker()->uuid;
    }

    public static function name(): string
    {
        return self::faker()->name;
    }

    public static function email(): string
    {
        return self::faker()->email;
    }

    private static function faker(): Generator
    {
        return self::$faker ?? Factory::create();
    }
}
