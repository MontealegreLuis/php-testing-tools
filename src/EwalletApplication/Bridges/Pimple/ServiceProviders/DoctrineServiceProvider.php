<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DoctrineServiceProvider implements ServiceProviderInterface
{
    /**
     * Register Doctrine's entity manager
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['doctrine.em'] = function () use ($container) {
            $configuration = Setup::createXMLMetadataConfiguration(
                $container['doctrine']['mapping_dirs'],
                $container['doctrine']['dev_mode'],
                $container['doctrine']['proxy_dir']
            );
            $entityManager = EntityManager::create(
                $container['doctrine']['connection'], $configuration
            );

            $platform = $entityManager->getConnection()->getDatabasePlatform();
            foreach ($container['doctrine']['types'] as $type => $class) {
                !Type::hasType($type) && Type::addType($type, $class);
                $platform->registerDoctrineTypeMapping($type, $type);
            }

            return $entityManager;
        };
    }
}
