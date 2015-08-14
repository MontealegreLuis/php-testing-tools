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
     * Registers Doctrine's entity manager
     *
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['doctrine.em'] = function () use ($pimple) {
            $configuration = Setup::createXMLMetadataConfiguration(
                $pimple['doctrine']['mapping_dirs'],
                $pimple['doctrine']['dev_mode'],
                $pimple['doctrine']['proxy_dir']
            );
            $entityManager = EntityManager::create(
                $pimple['doctrine']['connection'], $configuration
            );

            $platform = $entityManager->getConnection()->getDatabasePlatform();
            foreach ($pimple['doctrine']['types'] as $type => $class) {
                !Type::hasType($type) && Type::addType($type, $class);
                $platform->registerDoctrineTypeMapping($type, $type);
            }

            return $entityManager;
        };
    }
}
