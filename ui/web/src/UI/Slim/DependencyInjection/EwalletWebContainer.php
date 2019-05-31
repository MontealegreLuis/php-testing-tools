<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\DependencyInjection;

use Application\DependencyInjection\ApplicationServiceProvider;
use Application\DependencyInjection\EwalletWebServiceProvider;
use Application\DependencyInjection\MiddlewareServiceProvider;
use Adapters\Pimple\Application\DependencyInjection\DoctrineServiceProvider;
use Adapters\Pimple\Application\DependencyInjection\TwigServiceProvider;
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
