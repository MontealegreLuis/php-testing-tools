<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Doctrine2;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\{EntityManager, EntityManagerInterface};
use Doctrine\ORM\Tools\{SchemaTool, Setup};

trait ProvidesDoctrineSetup
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function _entityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * Setup XML mapping configuration
     * Configure entity manager
     * Add custom types
     */
    public function _setUpDoctrine(array $options): void
    {
        if ($this->entityManager) { // Do not initialize twice
            return;
        }

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

    public function _updateDatabaseSchema(array $options): void
    {
        $this->_setUpDoctrine($options);
        $tool = new SchemaTool($em = $this->_entityManager());
        $tool->updateSchema($em->getMetadataFactory()->getAllMetadata(), true);
    }

    public function _repositoryForEntity(string $class): ObjectRepository
    {
        return $this->_entityManager()->getRepository($class);
    }
}
