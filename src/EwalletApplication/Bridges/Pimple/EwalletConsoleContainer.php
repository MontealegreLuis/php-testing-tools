<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple;

use EwalletApplication\Bridges\Pimple\ServiceProviders\DoctrineServiceProvider;
use EwalletApplication\Bridges\Pimple\ServiceProviders\EwalletConsoleServiceProvider;
use Pimple\Container;

class EwalletConsoleContainer extends Container
{
    /**
     * Add service providers and application options.
     *
     * @param array $values
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->register(new DoctrineServiceProvider());
        $this->register(new EwalletConsoleServiceProvider());
    }
}
