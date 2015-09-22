<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use Ewallet\Accounts\Member;
use Ewallet\Bridges\Hexagonal\Wallet\TransferFundsTransactionally;
use EwalletModule\Bridges\Monolog\LogTransferWasMadeSubscriber;
use EwalletModule\Bridges\Twig\TwigTemplateEngine;
use EwalletModule\Bridges\Zf2\Mail\EmailTransferWasMadeSubscriber;
use EwalletModule\Bridges\Zf2\Mail\TransportFactory;
use EwalletModule\View\MemberFormatter;
use Hexagonal\Bridges\Doctrine2\Application\Services\DoctrineSession;
use Hexagonal\DomainEvents\EventPublisher;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
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
            $publisher->subscribe($container['ewallet.transfer_mail_notifier']);

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
        $container['ewallet.transfer_mail_notifier'] = function () use ($container) {
            return new EmailTransferWasMadeSubscriber(
                $container['ewallet.member_repository'],
                $container['ewallet.template_engine'],
                (new TransportFactory())->buildTransport($container['mail'])
            );
        };
    }
}
