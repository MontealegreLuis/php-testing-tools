<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\SymfonyConsole\Commands;

use Hexagonal\Messaging\MessagePublisher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
            ->addArgument(
                'exchange',
                InputArgument::OPTIONAL,
                'Exchange name to publish events to',
                'ewallet'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = $this->publisher->publishTo($input->getArgument('exchange'));

        $output->writeln(sprintf(
            '<comment>%d</comment> <info>messages published!</info>',
            $messages
        ));
    }
}
