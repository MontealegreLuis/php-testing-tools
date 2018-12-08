<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Application\DependencyInjection\DoctrineServiceProvider;
use Application\DependencyInjection\TwigServiceProvider;
use Ewallet\Pimple\ServiceProviders\EwalletMessagingServiceProvider;
use Ewallet\Pimple\ServiceProviders\MessagingServiceProvider;
use Pimple\Container;

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
