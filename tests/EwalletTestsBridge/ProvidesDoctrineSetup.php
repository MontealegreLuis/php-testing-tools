<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletTestsBridge;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

trait ProvidesDoctrineSetup
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * Setup XML mapping configuration
     * Configure entity manager
     * Add custom types
     */
    protected function setUpDoctrine()
    {
        $options = require __DIR__ . '/../../app/config.php';

        $configuration = Setup::createXMLMetadataConfiguration(
            $options['doctrine']['mapping_dirs'],
            $options['doctrine']['dev_mode'],
            $options['doctrine']['proxy_dir']
        );
        $this->entityManager = EntityManager::create(
            $options['doctrine']['connection'], $configuration
        );

        $platform = $this->entityManager->getConnection()->getDatabasePlatform();
        foreach ($options['doctrine']['types'] as $type => $class) {
            !Type::hasType($type) && Type::addType($type, $class);
            $platform->registerDoctrineTypeMapping($type, $type);
        }
    }
}
