<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\SymfonyConsole\Commands;

use Ewallet\Accounts\MemberInformation;
use Ewallet\Wallet\TransferFunds;
use Ewallet\Wallet\TransferFundsNotifier;
use Ewallet\Wallet\TransferFundsRequest;
use Ewallet\Wallet\TransferFundsResponse;
use EwalletModule\View\MemberFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransferFundsCommand extends Command implements TransferFundsNotifier
{
    /** @var TransferFunds */
    private $useCase;

    /** @var MemberFormatter */
    private $formatter;

    /** @var OutputInterface */
    private $output;

    /**
     * @param TransferFunds $useCase
     * @param EwalletExtension $formatter
     */
    public function __construct(TransferFunds $useCase, MemberFormatter $formatter)
    {
        parent::__construct();
        $this->formatter = $formatter;
        $this->useCase = $useCase;
        $this->useCase->attach($this);
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('ewallet:transfer')
            ->setDescription('Transfer funds from a member to another')
            ->addArgument('fromMemberId', InputArgument::REQUIRED, 'The ID of the member making the transfer')
            ->addArgument('toMemberId', InputArgument::REQUIRED, 'The ID of the member that will receive funds')
            ->addArgument('amount', InputArgument::REQUIRED, 'The amount to be transferred')
        ;
    }

    /**
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        $this->useCase->transfer(TransferFundsRequest::from($input->getArguments()));
    }

    /**
     * @param TransferFundsResponse $response
     */
    public function transferCompleted(TransferFundsResponse $response)
    {
        $this->output->writeln('<info>Transfer completed successfully!</info>');
        $this->printStatement($response->fromMember());
        $this->printStatement($response->toMember());
    }

    /**
     * @param MemberInformation $forMember
     */
    private function printStatement(MemberInformation $forMember)
    {
        $this->output->writeln("{$this->formatter->renderMember($forMember)}");
    }
}
