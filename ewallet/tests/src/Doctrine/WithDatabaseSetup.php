<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Doctrine;

use Adapters\Symfony\Application\DependencyInjection\ContainerFactory;
use Application\BasePath;
use Application\Environment;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use SplFileInfo;

trait WithDatabaseSetup
{
    protected DataStorageSetup $setup;

    protected ContainerInterface $container;

    public function _setupDatabaseSchema(SplFileInfo $path): void
    {
        $basePath = new BasePath($path);
        $environment = new Environment('test', true);
        $this->container = ContainerFactory::create($basePath, $environment);
        $this->setup = new DataStorageSetup($this->container->get(EntityManager::class));
        $this->setup->updateSchema();
    }

    public function _executeDqlQuery(string $query): void
    {
        $this->setup->entityManager()->createQuery($query)->execute();
    }
}
