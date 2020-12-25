<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Pimple\Application\DependencyInjection;

use Adapters\Doctrine\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

final class DoctrineServiceProvider implements ServiceProviderInterface
{
    /**
     * Register Doctrine's entity manager
     */
    public function register(Container $container): void
    {
        $container[EntityManagerInterface::class] = static function () use ($container): EntityManager {
            return EntityManagerFactory::create($container['doctrine']);
        };
    }
}
