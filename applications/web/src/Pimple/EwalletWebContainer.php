<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple;

use Ewallet\Pimple\ServiceProviders\DoctrineServiceProvider;
use Ewallet\Pimple\ServiceProviders\EwalletWebServiceProvider;
use Ewallet\Pimple\ServiceProviders\FormsServiceProvider;
use Ewallet\Pimple\ServiceProviders\TwigServiceProvider;
use Pimple\Container;
use Slim\Slim;

class EwalletWebContainer extends Container
{
    /**
     * Add service providers and application options.
     *
     * @param array $values
     * @param Slim $app
     */
    public function __construct(array $values = [], Slim $app)
    {
        parent::__construct($values);
        $this->register(new DoctrineServiceProvider());
        $this->register(new TwigServiceProvider());
        $this->register(new FormsServiceProvider());
        $this->register(new EwalletWebServiceProvider($app));
    }
}
