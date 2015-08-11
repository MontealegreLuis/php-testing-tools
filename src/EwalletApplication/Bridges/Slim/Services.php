<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim;

use EwalletApplication\Bridges\Slim\ServiceProviders\EwalletServiceProvider;
use EwalletApplication\Bridges\Slim\ServiceProviders\FormsServiceProvider;
use EwalletApplication\Bridges\Slim\ServiceProviders\DoctrineServiceProvider;
use EwalletApplication\Bridges\Slim\ServiceProviders\MiddlewareServiceProvider;
use EwalletApplication\Bridges\Slim\ServiceProviders\MonologServiceProvider;
use EwalletApplication\Bridges\Slim\ServiceProviders\TwigServiceProvider;

class Services extends \ComPHPPuebla\Slim\Services
{
    public function init()
    {
        $this
            ->add(new DoctrineServiceProvider())
            ->add(new TwigServiceProvider())
            ->add(new FormsServiceProvider())
            ->add(new EwalletServiceProvider())
            ->add(new MonologServiceProvider())
            ->add(new MiddlewareServiceProvider())
        ;
    }
}
