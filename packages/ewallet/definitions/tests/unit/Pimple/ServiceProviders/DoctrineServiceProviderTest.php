<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Doctrine\ORM\EntityManager;
use PHPUnit_Framework_TestCase as TestCase;
use Pimple\Container;

class DoctrineServiceProviderTest extends TestCase
{
    /** @test */
    function it_creates_the_entity_manager()
    {
        $options = require __DIR__ . '/../../../../config.php';
        $container = new Container($options);
        $container->register(new DoctrineServiceProvider());

        $this->assertInstanceOf(
            EntityManager::class,
            $container['doctrine.em']
        );
    }
}
