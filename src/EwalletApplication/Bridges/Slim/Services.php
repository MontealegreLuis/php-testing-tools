<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim;

use EwalletApplication\Bridges\Slim\ServiceProviders\MiddlewareServiceProvider;
use EwalletApplication\Bridges\Slim\ServiceProviders\ApplicationServiceProvider;

class Services extends \ComPHPPuebla\Slim\Services
{
    public function init()
    {
        $this
            ->add(new ApplicationServiceProvider())
            ->add(new MiddlewareServiceProvider())
        ;
    }
}
