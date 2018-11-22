<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Application\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Ewallet\ManageWallet\TransferFunds\TransactionalTransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\Memberships\Members;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Ports\Doctrine\Application\Services\DoctrineSession;
use Ports\Doctrine\Ewallet\Memberships\MembersRepository;

class EwalletServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[TransferFundsAction::class] =  function () use ($container) {
            $transferFunds = new TransactionalTransferFundsAction($container[Members::class]);
            $transferFunds->setTransactionalSession(new DoctrineSession($container[EntityManagerInterface::class]));

            return $transferFunds;
        };
        $container[Members::class] = function () use ($container) {
            return new MembersRepository($container[EntityManagerInterface::class]);
        };
    }
}
