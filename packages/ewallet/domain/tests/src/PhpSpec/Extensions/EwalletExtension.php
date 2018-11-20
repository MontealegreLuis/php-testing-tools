<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\PhpSpec\Extensions;

use Ewallet\PhpSpec\Matchers\MoneyMatcher;
use PhpSpec\Extension;
use PhpSpec\ServiceContainer;

class EwalletExtension implements Extension
{
    public function load(ServiceContainer $container, array $params)
    {
        $container->define(
            'ewallet.matchers.amount',
            function () {
                return new MoneyMatcher();
            },
            ['matchers']
        );
    }
}
