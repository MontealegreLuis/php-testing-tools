<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Application\DependencyInjection\EwalletServiceProvider;
use Application\DomainEvents\EventPublisher;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\ManageWallet\TransferFundsConsole;
use Ewallet\SymfonyConsole\Commands\TransferFundsCommand;
use Ewallet\SymfonyConsole\Listeners\StoreEventsListener;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EwalletConsoleServiceProvider extends EwalletServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * console command
     */
    public function register(Container $container)
    {
        parent::register($container);
        $container[TransferFundsCommand::class] = function () use ($container) {
            return new TransferFundsCommand(
                $container[TransferFundsAction::class],
                $container[TransferFundsConsole::class]
            );
        };
        $container[InputInterface::class] = function () {
            return new ArgvInput();
        };
        $container[OutputInterface::class] = function () {
            return new ConsoleOutput();
        };
        $container[EventPublisher::class] = function () use ($container) {
            $publisher = new EventPublisher();
//            $publisher->subscribe($container[LoggerInterface::class]);

            return $publisher;
        };
        $container[LoggerInterface::class] = function () use ($container) {
            $logger = new Logger($container['monolog']['ewallet']['channel']);
            $logger->pushHandler(new SyslogHandler(
                $container['monolog']['ewallet']['channel'],
                LOG_USER,
                Logger::DEBUG
            ));

            return $logger;
        };
        $container[EventDispatcher::class] = function () use ($container) {
            $dispatcher = new EventDispatcher();
            //$dispatcher->addListener(ConsoleEvents::COMMAND,  $container['ewallet.store_events_listener']);

            return $dispatcher;
        };
        $container['ewallet.store_events_listener'] = function () use ($container) {
            return new StoreEventsListener(
                $container['ewallet.event_persist_subscriber'],
                $container['ewallet.events_publisher']
            );
        };
    }
}
