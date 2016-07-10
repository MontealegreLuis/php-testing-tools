<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Doctrine2;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, Tools\Setup};

trait ProvidesDoctrineSetup
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @return EntityManagerInterface
     */
    public function _entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * Setup XML mapping configuration
     * Configure entity manager
     * Add custom types
     *
     * @param array $options
     */
    public function _setUpDoctrine(array $options)
    {
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
