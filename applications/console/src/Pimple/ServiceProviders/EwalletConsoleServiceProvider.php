<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\SymfonyConsole\Listeners\StoreEventsListener;
use Ewallet\Actions\TransferFundsAction;
use Ewallet\Responders\TransferFundsConsoleResponder;
use Ewallet\Accounts\Member;
use Ewallet\Wallet\TransferFundsTransactionally;
use Ewallet\Actions\Notifications\TransferFundsEmailNotifier;
use Ewallet\Monolog\LogTransferWasMadeSubscriber;
use Ewallet\View\MemberFormatter;
use Ewallet\Pimple\ServiceProviders\EwalletServiceProvider;
use Hexagonal\Doctrine2\Application\Services\DoctrineSession;
use Hexagonal\JmsSerializer\JsonSerializer;
use Hexagonal\DomainEvents\EventPublisher;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\DomainEvents\StoredEventFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class EwalletConsoleServiceProvider extends EwalletServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * console command
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        parent::register($container);
        $container['ewallet.console_input'] = function () {
            return new ArgvInput();
        };
        $container['ewallet.console_output'] = function () {
            return new ConsoleOutput();
        };
        $container['ewallet.transfer_funds_console_responder'] = function () use ($container) {
            return new TransferFundsConsoleResponder(
                $container['ewallet.console_input'],
                $container['ewallet.console_output'],
                new QuestionHelper(),
                $container['ewallet.member_repository'],
                $container['ewallet.member_formatter']
            );
        };
        $container['ewallet.transfer_funds_console_action'] = function () use ($container) {
            return new TransferFundsAction(
                $container['ewallet.transfer_funds_console_responder'],
                $container['ewallet.transfer_funds']
            );
        };
        $container['ewallet.events_publisher'] = function () use ($container) {
            $publisher = new EventPublisher();
            $publisher->subscribe($container['ewallet.transfer_funds_logger']);

            return $publisher;
        };
        $container['ewallet.event_store'] = function () use ($container) {
            return $container['doctrine.em']->getRepository(StoredEvent::class);
        };
        $container['ewallet.event_persist_subscriber'] = function () use ($container) {
            return new PersistEventsSubscriber(
                $container['ewallet.event_store'],
                new StoredEventFactory(new JsonSerializer())
            );
        };
        $container['ewallet.store_events_listener'] = function () use ($container) {
            return new StoreEventsListener(
                $container['ewallet.event_persist_subscriber'],
                $container['ewallet.events_publisher']
            );
        };
        $container['ewallet.console.dispatcher'] = function () use ($container) {
            $dispatcher = new EventDispatcher();
            $dispatcher->addListener(
                ConsoleEvents::COMMAND,  $container['ewallet.store_events_listener']
            );

            return $dispatcher;
        };
    }
}
