<?php
/**
 * PHP version 7.0
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
     * @param MessagePublisher $publisher
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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = $this->publisher->publishTo('ewallet');

        $output->writeln(sprintf(
            '<comment>%d</comment> <info>messages published!</info>',
            $messages
        ));
    }
}
