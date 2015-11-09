<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use EwalletApplication\Bridges\SymfonyConsole\Listeners\StoreEventsListener;
use EwalletModule\Bridges\Twig\Extensions\EwalletExtension;
use Ewallet\Accounts\Member;
use Ewallet\Bridges\Hexagonal\Wallet\TransferFundsTransactionally;
use EwalletModule\Actions\Notifications\TransferFundsEmailNotifier;
use EwalletModule\Bridges\Monolog\LogTransferWasMadeSubscriber;
use EwalletModule\Bridges\Twig\TwigTemplateEngine;
use EwalletModule\Bridges\Zf2\Mail\TransferFundsZendMailSender;
use EwalletModule\Bridges\Zf2\Mail\TransportFactory;
use EwalletModule\View\MemberFormatter;
use Hexagonal\Bridges\Doctrine2\Application\Services\DoctrineSession;
use Hexagonal\Bridges\JmsSerializer\JsonSerializer;
use Hexagonal\DomainEvents\EventPublisher;
use Hexagonal\DomainEvents\PersistEventsSubscriber;
use Hexagonal\DomainEvents\StoredEvent;
use Hexagonal\DomainEvents\StoredEventFactory;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Twig_Loader_Filesystem as Loader;
use Twig_Environment as Environment;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class EwalletConsoleServiceProvider implements ServiceProviderInterface
{
    /**
     * Register the services for Transfer Funds feature delivered through a
     * console command
     *
     * @param Container $container
     */
    public function register(Container $container)
    {
        $container['ewallet.member_repository'] = function () use ($container) {
            return $container['doctrine.em']->getRepository(Member::class);
        };
        $container['ewallet.template_engine'] = function () use ($container) {
            return new TwigTemplateEngine($container['twig.environment']);
        };
        $container['ewallet.transfer_funds'] =  function () use ($container) {
            $transferFunds = new TransferFundsTransactionally(
                $container['ewallet.member_repository']
            );
            $transferFunds->setTransactionalSession(new DoctrineSession(
                $container['doctrine.em']
            ));
            $transferFunds->setPublisher($container['ewallet.events_publisher']);

            return $transferFunds;
        };
        $container['ewallet.member_formatter'] = function () {
            return new MemberFormatter();
        };
        $container['ewallet.events_publisher'] = function () use ($container) {
            $publisher = new EventPublisher();
            $publisher->subscribe($container['ewallet.transfer_funds_logger']);

            return $publisher;
        };
        $container['ewallet.transfer_funds_logger'] = function () use ($container) {
            return new LogTransferWasMadeSubscriber(
                $container['ewallet.logger'], $container['ewallet.member_formatter']
            );
        };
        $container['ewallet.logger'] = function () use ($container) {
            $logger = new Logger($container['monolog']['ewallet']['channel']);
            $logger->pushHandler(new StreamHandler(
                $container['monolog']['ewallet']['path'], Logger::DEBUG
            ));

            return $logger;
        };
        $container['ewallet.transfer_mail_sender'] = function () use ($container) {
            return new TransferFundsZendMailSender(
                $container['ewallet.template_engine'],
                (new TransportFactory())->buildTransport($container['mail'])
            );
        };
        $container['ewallet.transfer_mail_notifier'] = function () use ($container) {
            return new TransferFundsEmailNotifier(
                $container['ewallet.member_repository'],
                $container['ewallet.transfer_mail_sender']
            );
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
        $container['ewallet.twig.extension'] = function () {
            return new EwalletExtension(new MemberFormatter());
        };
        $container->extend(
            'twig.loader',
            function (Loader $loader) {
                $loader->addPath(
                    __DIR__ . '/../../../../EwalletModule/Bridges/Twig/Resources/templates'
                );
                $loader->addPath(
                    __DIR__ . '/../../SymfonyConsole/Resources/templates'
                );

                return $loader;
            }
        );
        $container->extend(
            'twig.environment',
            function (Environment $twig) use ($container) {
                $twig->addExtension($container['ewallet.twig.extension']);

                return $twig;
            }
        );
    }
}
