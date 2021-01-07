<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\UI\Console;

use Ewallet\UI\Console\Commands\TransferFundsCommand;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;

final class EwalletApplication extends Application
{
    public static function fromContainer(ContainerInterface $container): EwalletApplication
    {
        $application = new self('ewallet', '1.0.0');
        $application->add($container->get(TransferFundsCommand::class));
        return $application;
    }
}
