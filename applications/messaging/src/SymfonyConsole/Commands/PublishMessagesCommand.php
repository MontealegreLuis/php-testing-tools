<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Hexagonal\Messaging\MessagePublisher;
use Symfony\Component\Console\{
    Command\Command, Input\InputInterface, Output\OutputInterface
};

class PublishMessagesCommand extends Command
{
    /** @var MessagePublisher */
    private $publisher;

    /**
     * @throws \Symfony\Component\Console\Exception\LogicException if the command
     * name is empty
     */
    public function __construct(MessagePublisher $publisher)
    {
        parent::__construct();
        $this->publisher = $publisher;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('ewallet:events:spread')
            ->setDescription('Spread domain events via messaging')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = $this->publisher->publishTo('ewallet');

        $output->writeln(sprintf(
            '<comment>%d</comment> <info>messages published!</info>',
            $messages
        ));
    }
}
