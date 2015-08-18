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
use EwalletModule\View\MemberFormatter;
use Hexagonal\Bridges\Doctrine2\Application\Services\DoctrineSession;
use Hexagonal\DomainEvents\EventPublisher;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Zend\Mail\Transport\File;
use Zend\Mail\Transport\FileOptions;

class EwalletConsoleServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $pimple
     */
    public function register(Container $pimple)
    {
        $pimple['ewallet.member_repository'] = function () use ($pimple) {
            return $pimple['doctrine.em']->getRepository(Member::class);
        };
        $pimple['ewallet.template_engine'] = function () use ($pimple) {
            return new TwigTemplateEngine($pimple['twig.environment']);
        };
        $pimple['ewallet.transfer_funds'] =  function () use ($pimple) {
            $transferFunds = new TransferFundsTransactionally(
                $pimple['ewallet.member_repository']
            );
            $transferFunds->setTransactionalSession(new DoctrineSession(
                $pimple['doctrine.em']
            ));
            $transferFunds->setPublisher($pimple['ewallet.events_publisher']);

            return $transferFunds;
        };
        $pimple['ewallet.member_formatter'] = function () {
            return new MemberFormatter();
        };
        $pimple['ewallet.events_publisher'] = function () use ($pimple) {
            $publisher = new EventPublisher();
            $publisher->subscribe($pimple['ewallet.transfer_funds_logger']);
            $publisher->subscribe($pimple['ewallet.transfer_mail_notifier']);

            return $publisher;
        };
        $pimple['ewallet.transfer_funds_logger'] = function () use ($pimple) {
            return new LogTransferWasMadeSubscriber(
                $pimple['ewallet.logger'], $pimple['ewallet.member_formatter']
            );
        };
        $pimple['ewallet.logger'] = function () use ($pimple) {
            $logger = new Logger($pimple['monolog']['ewallet']['channel']);
            $logger->pushHandler(new StreamHandler(
                $pimple['monolog']['ewallet']['path'], Logger::DEBUG
            ));

            return $logger;
        };
        $pimple['ewallet.transfer_mail_notifier'] = function () use ($pimple) {
            return new EmailTransferWasMadeSubscriber(
                $pimple['ewallet.member_repository'],
                $pimple['ewallet.template_engine'],
                new File(new FileOptions([
                    'path' => $pimple['mail']['path'],
                    'callback'  => function () {
                        return 'message-' . microtime(true) . '-' . mt_rand() . '.html';
                    }
                ]))
            );
        };
    }
}
