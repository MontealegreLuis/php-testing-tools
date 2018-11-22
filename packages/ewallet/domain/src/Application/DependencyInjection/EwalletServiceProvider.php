<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Ewallet\ManageWallet\TransferFunds\TransactionalTransferFundsAction;
use Hexagonal\DomainEvents\EventPublisher;
use Pimple\{Container, ServiceProviderInterface};
use Ports\Doctrine\Application\Services\DoctrineSession;
use Ports\Doctrine\Ewallet\Memberships\MembersRepository;

class EwalletServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['ewallet.transfer_funds'] =  function () use ($container) {
            $transferFunds = new TransactionalTransferFundsAction($container['ewallet.member_repository']);
            $transferFunds->setTransactionalSession(new DoctrineSession($container['doctrine.em']));
            $transferFunds->setPublisher($container['ewallet.events_publisher']);

            return $transferFunds;
        };
        $container['ewallet.member_repository'] = function () use ($container) {
            return new MembersRepository($container['doctrine.em']);
        };
        $container['ewallet.events_publisher'] = function () {
            return new EventPublisher();
        };
    }
}
