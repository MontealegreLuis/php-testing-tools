<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Setup;

use Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Psr\Container\ContainerInterface;
use Setup\Commands\CreateDatabaseCommand;
use Setup\Commands\DropDatabaseCommand;
use Setup\Commands\RefreshDatabase;
use Setup\Commands\SeedDatabaseCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

final class SetupApplication extends Application
{
    public static function fromContainer(ContainerInterface $container): SetupApplication
    {
        $application = new self('EWallet Application Setup', 'v1.0.0');
        $application->setHelperSet(new HelperSet([
            'em' => $container->get(EntityManagerHelper::class),
        ]));
        $application->add($container->get(DropDatabaseCommand::class));
        $application->add($container->get(CreateDatabaseCommand::class));
        $application->add($container->get(SeedDatabaseCommand::class));
        $application->add($container->get(UpdateCommand::class));
        $application->add($container->get(RefreshDatabase::class));

        return $application;
    }
}
