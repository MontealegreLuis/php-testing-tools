<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
require __DIR__ . '/../vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Dotenv\Dotenv;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Ewallet\SymfonyConsole\Commands\{
    CreateDatabaseCommand, DropDatabaseCommand, RefreshDatabase, SeedDatabaseCommand
};
use Symfony\Component\Console\{Application, Helper\HelperSet};

$environment = new Dotenv(__DIR__ . '/../');
$environment->load();
$environment->required([
    'APP_ENV',
    'MYSQL_USER',
    'MYSQL_PASSWORD',
    'MYSQL_HOST',
]);

$setup = new class() { use ProvidesDoctrineSetup; };
$setup->_setUpDoctrine(require __DIR__ . '/../config.php');

$application = new Application();
$application->setHelperSet(new HelperSet([
    'db' => new ConnectionHelper($setup->_entityManager()->getConnection()),
    'em' => new EntityManagerHelper($setup->_entityManager()),
]));
$application->add(new DropDatabaseCommand());
$application->add(new CreateDatabaseCommand());
$application->add(new SeedDatabaseCommand($setup->_entityManager()));
$application->add(new UpdateCommand());
$application->add(new RefreshDatabase());

$application->run();
