<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class DoctrineServiceProviderTest extends TestCase
{
    /** @test */
    function it_creates_the_entity_manager()
    {
        $options = require __DIR__ . '/../../../config.php';
        $container = new Container($options);
        $container->register(new DoctrineServiceProvider());

        $this->assertInstanceOf(EntityManagerInterface::class, $container[EntityManagerInterface::class]);
    }
}
