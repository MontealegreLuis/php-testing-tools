<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Fakes\Application;

use Application\Clock;
use Carbon\CarbonImmutable;

class FakeClock implements Clock
{
    private CarbonImmutable $now;

    public function __construct(CarbonImmutable $now)
    {
        $this->now = $now;
    }

    public function now(): CarbonImmutable
    {
        return $this->now;
    }
}
