<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\SymfonyConsole;

use EwalletApplication\Bridges\Pimple\EwalletContainer;
use EwalletApplication\Bridges\SymfonyConsole\Commands\TransferFundsCommand;
use Symfony\Component\Console\Application;

class EwalletApplication extends Application
{
    /**
     * @param EwalletContainer $container
     */
    public function __construct(EwalletContainer $container)
    {
        parent::__construct('ewallet', '1.0.0');
        $this
            ->add(new TransferFundsCommand(
                $container['ewallet.transfer_funds'],
                $container['ewallet.twig.extension']
            ))
        ;
    }
}
