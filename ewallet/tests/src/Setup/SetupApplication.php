<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup;

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\DataStorageSetup;
use Setup\Commands\CreateDatabaseCommand;
use Setup\Commands\DropDatabaseCommand;
use Setup\Commands\RefreshDatabase;
use Setup\Commands\SeedDatabaseCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

class SetupApplication extends Application
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public function __construct(array $options)
    {
        parent::__construct('EWallet Application Setup', 'v1.0.0');
        $setup = new DataStorageSetup($options);
        $entityManager = $setup->entityManager();
        $this->setHelperSet(new HelperSet([
            'db' => new ConnectionHelper($entityManager->getConnection()),
            'em' => new EntityManagerHelper($entityManager),
        ]));
        $this->add(new DropDatabaseCommand());
        $this->add(new CreateDatabaseCommand());
        $this->add(new SeedDatabaseCommand());
        $this->add((new UpdateCommand())->setHidden(true));
        $this->add(new RefreshDatabase());
    }
}