<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application;

use Carbon\CarbonImmutable;

interface Clock
{
    public function now(): CarbonImmutable;
}
