<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Application\DomainEvents\EventPublisher;
use Application\DomainEvents\PersistEventsSubscriber;
use Application\DomainEvents\StoredEventFactory;
use Doctrine\ORM\EntityManagerInterface;
use Ewallet\ManageWallet\TransferFunds\TransactionalTransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\Memberships\Members;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Ports\Doctrine\Application\DomainEvents\EventStoreRepository;
use Ports\Doctrine\Application\Services\DoctrineSession;
use Ports\Doctrine\Ewallet\Memberships\MembersRepository;
use Ports\JmsSerializer\Application\DomainEvents\JsonSerializer;

class EwalletServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[TransferFundsAction::class] =  function () use ($container) {
            $transferFunds = new TransactionalTransferFundsAction($container[Members::class]);
            $transferFunds->setTransactionalSession(new DoctrineSession($container[EntityManagerInterface::class]));
            $transferFunds->setPublisher($container[EventPublisher::class]);
            return $transferFunds;
        };
        $container[EventPublisher::class] = function () use ($container) {
            $publisher = new EventPublisher();
            $publisher->subscribe($container[PersistEventsSubscriber::class]);

            return $publisher;
        };
        $container[PersistEventsSubscriber::class] = function () use ($container) {
            return new PersistEventsSubscriber(
                $container[EventStoreRepository::class],
                new StoredEventFactory(new JsonSerializer())
            );
        };
        $container[EventStoreRepository::class] = function () use ($container) {
            return new EventStoreRepository($container[EntityManagerInterface::class]);
        };
        $container[Members::class] = function () use ($container) {
            return new MembersRepository($container[EntityManagerInterface::class]);
        };
    }
}
