<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace PhpSpec\Extensions;

use PhpSpec\Extension;
use PhpSpec\Matchers\MoneyMatcher;
use PhpSpec\ServiceContainer;

class EwalletExtension implements Extension
{
    /** @param mixed[] $params */
    public function load(ServiceContainer $container, array $params): void
    {
        $container->define(
            'ewallet.matchers.amount',
            fn () => new MoneyMatcher(),
            ['matchers']
        );
    }
}
