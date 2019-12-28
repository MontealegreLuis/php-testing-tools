<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console;

use Ewallet\Pimple\EwalletMessagingContainer;
use Ewallet\Ui\Console\Commands\NotifyTransferByEmailCommand;
use Ewallet\Ui\Console\Commands\PublishMessagesCommand;
use Symfony\Component\Console\Application;

class EwalletApplication extends Application
{
    public function __construct(EwalletMessagingContainer $container)
    {
        parent::__construct('ewallet', '1.0.0');
        $this
            ->add(new PublishMessagesCommand(
                $container['hexagonal.messages_publisher']
            ))
        ;
        $this
            ->add(new NotifyTransferByEmailCommand(
                $container['ewallet.transfer_mail_notifier'],
                $container['hexagonal.messages_consumer']
            ))
        ;
    }
}
