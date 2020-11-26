<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Pimple\Application\DependencyInjection;

use Adapters\Doctrine\Application\DomainEvents\EventStoreRepository;
use Adapters\Doctrine\Application\Services\DoctrineSession;
use Adapters\Doctrine\Ewallet\Memberships\MembersRepository;
use Adapters\JmsSerializer\Application\DomainEvents\JsonSerializer;
use Application\DomainEvents\EventPublisher;
use Application\DomainEvents\PersistEventsSubscriber;
use Application\DomainEvents\StoredEventFactory;
use Doctrine\ORM\EntityManagerInterface;
use Ewallet\ManageWallet\TransferFunds\TransactionalTransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\Memberships\Members;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class EwalletServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container[TransferFundsAction::class] =  static function () use ($container): TransferFundsAction {
            $transferFunds = new TransactionalTransferFundsAction($container[Members::class], $container[EventPublisher::class]);
            $transferFunds->setTransactionalSession(new DoctrineSession($container[EntityManagerInterface::class]));
            return $transferFunds;
        };
        $container[EventPublisher::class] = static function () use ($container): EventPublisher {
            $publisher = new EventPublisher();
            $publisher->subscribe($container[PersistEventsSubscriber::class]);

            return $publisher;
        };
        $container[PersistEventsSubscriber::class] = static function () use ($container): PersistEventsSubscriber {
            return new PersistEventsSubscriber(
                $container[EventStoreRepository::class],
                new StoredEventFactory(new JsonSerializer())
            );
        };
        $container[EventStoreRepository::class] = static function () use ($container): EventStoreRepository {
            return new EventStoreRepository($container[EntityManagerInterface::class]);
        };
        $container[Members::class] = static function () use ($container): Members {
            return new MembersRepository($container[EntityManagerInterface::class]);
        };
    }
}
