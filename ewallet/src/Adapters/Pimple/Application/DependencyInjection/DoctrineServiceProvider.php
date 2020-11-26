<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Pimple\Application\DependencyInjection;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DoctrineServiceProvider implements ServiceProviderInterface
{
    /**
     * Register Doctrine's entity manager
     */
    public function register(Container $container): void
    {
        $container[EntityManagerInterface::class] = static function () use ($container): EntityManager {
            $configuration = Setup::createXMLMetadataConfiguration(
                $container['doctrine']['mapping_dirs'],
                $container['doctrine']['dev_mode'],
                $container['doctrine']['proxy_dir']
            );
            $entityManager = EntityManager::create($container['doctrine']['connection'], $configuration);

            $platform = $entityManager->getConnection()->getDatabasePlatform();
            foreach ($container['doctrine']['types'] as $type => $class) {
                if (! Type::hasType($type)) {
                    Type::addType($type, $class);
                }
                $platform->registerDoctrineTypeMapping($type, $type);
            }

            return $entityManager;
        };
    }
}
