<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Ewallet\Pimple\ServiceProviders\EwalletMessagingServiceProvider;
use Ewallet\Pimple\ServiceProviders\MessagingServiceProvider;
use Pimple\Container;
use Ports\Pimple\Application\DependencyInjection\DoctrineServiceProvider;
use Ports\Pimple\Application\DependencyInjection\TwigServiceProvider;

class EwalletMessagingContainer extends Container
{
    /**
     * Add service providers and application options.
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->register(new DoctrineServiceProvider());
        $this->register(new TwigServiceProvider());
        $this->register(new MessagingServiceProvider());
        $this->register(new EwalletMessagingServiceProvider());
    }
}
