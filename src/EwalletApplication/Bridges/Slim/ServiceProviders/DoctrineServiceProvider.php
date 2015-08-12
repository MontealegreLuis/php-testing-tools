<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Slim\ServiceProviders;

use ComPHPPuebla\Slim\Resolver;
use ComPHPPuebla\Slim\ServiceProvider;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Slim\Slim;

class DoctrineServiceProvider implements ServiceProvider
{
    /**
     * @param Slim $app
     * @param Resolver $resolver
     * @param array $options
     */
    public function configure(Slim $app, Resolver $resolver, array $options = [])
    {
        $app->container->singleton(
            'doctrine.em',
            function () use ($options) {
                $configuration = Setup::createXMLMetadataConfiguration(
                    $options['doctrine']['mapping_dirs'],
                    $options['doctrine']['dev_mode'],
                    $options['doctrine']['proxy_dir']
                );
                $entityManager = EntityManager::create(
                    $options['doctrine']['connection'], $configuration
                );

                $platform = $entityManager->getConnection()->getDatabasePlatform();
                foreach ($options['doctrine']['types'] as $type => $class) {
                    !Type::hasType($type) && Type::addType($type, $class);
                    $platform->registerDoctrineTypeMapping($type, $type);
                }

                return $entityManager;
            }
        );
    }
}
