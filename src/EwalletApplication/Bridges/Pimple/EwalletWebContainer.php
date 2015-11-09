<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 *
 * @copyright  Mandrágora Web-Based Systems 2015 (http://www.mandragora-web-systems.com)
 */
namespace EwalletApplication\Bridges\Pimple;

use EwalletApplication\Bridges\Pimple\ServiceProviders\DoctrineServiceProvider;
use EwalletApplication\Bridges\Pimple\ServiceProviders\EwalletWebServiceProvider;
use EwalletApplication\Bridges\Pimple\ServiceProviders\FormsServiceProvider;
use EwalletApplication\Bridges\Pimple\ServiceProviders\TwigServiceProvider;
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
