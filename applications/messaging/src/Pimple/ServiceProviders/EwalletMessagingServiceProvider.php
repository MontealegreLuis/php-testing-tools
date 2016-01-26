<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Pimple\ServiceProviders;

use Ewallet\Actions\TransferFundsAction;
use Ewallet\Accounts\Member;
use Ewallet\Wallet\TransferFundsTransactionally;
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

class EwalletMessagingServiceProvider extends EwalletServiceProvider implements ServiceProviderInterface
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
        $container->extend(
            'twig.loader',
            function (Loader $loader) {
                $loader->addPath(__DIR__ . '/../../Resources/templates');

                return $loader;
            }
        );
    }
}
