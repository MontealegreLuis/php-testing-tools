<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;

class TwigServiceProviderTest extends TestCase
{
    /** @test */
    function it_creates_twig_loader_and_environment()
    {
        $options = require __DIR__ . '/../../../../config.php';
        $container = new Container($options);
        $container->register(new TwigServiceProvider());

        $this->assertInstanceOf(
            Loader::class,
            $container['twig.loader']
        );
        $this->assertInstanceOf(
            Environment::class,
            $container['twig.environment']
        );
    }
}
