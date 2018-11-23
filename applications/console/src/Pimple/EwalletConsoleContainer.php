<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Application\DependencyInjection\DoctrineServiceProvider;
use Ewallet\Pimple\ServiceProviders\EwalletConsoleServiceProvider;
use Pimple\Container;

class EwalletConsoleContainer extends Container
{
    /**
     * Add service providers and application options.
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->register(new DoctrineServiceProvider());
        $this->register(new EwalletConsoleServiceProvider());
    }
}
