<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\Pimple\ServiceProviders;

use Ewallet\Accounts\Member;
use Ewallet\Bridges\Hexagonal\Wallet\TransferFundsTransactionally;
use EwalletModule\View\MemberFormatter;
use Hexagonal\Bridges\Doctrine2\Application\Services\DoctrineSession;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

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
        $pimple['ewallet.transfer_funds'] =  function () use ($pimple) {
            $transferFunds = new TransferFundsTransactionally(
                $pimple['ewallet.member_repository']
            );
            $transferFunds->setTransactionalSession(new DoctrineSession(
                $pimple['doctrine.em']
            ));

            return $transferFunds;
        };
        $pimple['ewallet.member_formatter'] = function () {
            return new MemberFormatter();
        };
    }
}
