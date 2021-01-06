<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console\Commands;

use Application\Messaging\MessagePublisher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PublishMessagesCommand extends Command
{
    /** @var MessagePublisher */
    private $publisher;

    public function __construct(MessagePublisher $publisher)
    {
        parent::__construct();
        $this->publisher = $publisher;
    }

    protected function configure(): void
    {
        $this
            ->setName('ewallet:events:spread')
            ->setDescription('Spread domain events via messaging')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messages = $this->publisher->publishTo('ewallet');

        $output->writeln(sprintf(
            '<comment>%d</comment> <info>messages published!</info>',
            $messages
        ));

        return 0;
    }
}
