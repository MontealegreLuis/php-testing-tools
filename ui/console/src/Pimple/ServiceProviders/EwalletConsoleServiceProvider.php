<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\ManageWallet\TransferFundsConsole;
use Ewallet\Memberships\MemberFormatter;
use Ewallet\SymfonyConsole\Commands\TransferFundsCommand;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Adapters\Pimple\Application\DependencyInjection\EwalletServiceProvider;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

class EwalletConsoleServiceProvider extends EwalletServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * console command
     */
    public function register(Container $container): void
    {
        parent::register($container);
        $container[TransferFundsCommand::class] = function () use ($container) {
            return new TransferFundsCommand(
                $container[TransferFundsAction::class],
                $container[TransferFundsConsole::class]
            );
        };
        $container[TransferFundsConsole::class] = function () use ($container) {
            return new TransferFundsConsole($container[OutputInterface::class], new MemberFormatter());
        };
        $container[InputInterface::class] = function () {
            return new ArgvInput();
        };
        $container[OutputInterface::class] = function () {
            return new ConsoleOutput();
        };
    }
}
