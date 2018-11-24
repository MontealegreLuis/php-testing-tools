<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple;

use Application\DependencyInjection\DoctrineServiceProvider;
use Ewallet\Pimple\ServiceProviders\ApplicationServiceProvider;
use Ewallet\Pimple\ServiceProviders\EwalletWebServiceProvider;
use Ewallet\Pimple\ServiceProviders\MiddlewareServiceProvider;
use Ewallet\Pimple\ServiceProviders\TwigServiceProvider;
use Slim\Container;

class EwalletWebContainer extends Container
{
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);
        $this->register(new DoctrineServiceProvider());
        $this->register(new TwigServiceProvider());
        $this->register(new EwalletWebServiceProvider());
        $this->register(new ApplicationServiceProvider());
        $this->register(new MiddlewareServiceProvider());
    }
}
