<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Adapters\Pimple\Application\DependencyInjection\DoctrineServiceProvider;
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
