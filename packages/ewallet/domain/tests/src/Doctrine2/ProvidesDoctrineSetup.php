<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Doctrine2;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\{EntityManager, EntityManagerInterface};
use Doctrine\ORM\Tools\{SchemaTool, Setup};

trait ProvidesDoctrineSetup
{
    /** @var EntityManagerInterface */
    private static $entityManager;

    public function _entityManager(): EntityManagerInterface
    {
        return self::$entityManager;
    }

    /**
     * Setup XML mapping configuration
     * Configure entity manager
     * Add custom types
     */
    public function _setUpDoctrine(array $options): void
    {
        if (self::$entityManager) { // Do not initialize twice
            return;
        }

        $configuration = Setup::createXMLMetadataConfiguration(
            $options['doctrine']['mapping_dirs'],
            $options['doctrine']['dev_mode'],
            $options['doctrine']['proxy_dir']
        );
        self::$entityManager = EntityManager::create(
            $options['doctrine']['connection'], $configuration
        );

        $platform = self::$entityManager->getConnection()->getDatabasePlatform();
        foreach ($options['doctrine']['types'] as $type => $class) {
            !Type::hasType($type) && Type::addType($type, $class);
            $platform->registerDoctrineTypeMapping($type, $type);
        }

        $tool = new SchemaTool(self::$entityManager);
        $tool->updateSchema(self::$entityManager->getMetadataFactory()->getAllMetadata(), true);
    }
}
