<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console;

use Ewallet\Pimple\EwalletConsoleContainer;
use Ewallet\Ui\Console\Commands\TransferFundsCommand;
use Symfony\Component\Console\Application;

class EwalletApplication extends Application
{
    public function __construct(EwalletConsoleContainer $container)
    {
        parent::__construct('ewallet', '1.0.0');
        $this->add($container[TransferFundsCommand::class]);
    }
}
