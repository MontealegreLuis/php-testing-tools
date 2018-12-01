<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Application\Actions\InputValidator;
use Ewallet\ManageWallet\TransferFunds\TransactionalTransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsInput;
use Ewallet\ManageWallet\TransferFunds\TransferFundsResponder;
use Ewallet\ManageWallet\TransferFunds\TransferFundsSummary;
use Ewallet\ManageWallet\TransferFundsConsole;
use Ewallet\Memberships\InsufficientFunds;
use Ewallet\Memberships\UnknownMember;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransferFundsCommand extends Command implements TransferFundsResponder
{
    private const SUCCESS = 0;
    private const ERROR = 1;

    /** @var int */
    private $exitCode;

    /** @var TransactionalTransferFundsAction */
    private $action;

    /** @var TransferFundsConsole */
    private $console;

    public function __construct(TransactionalTransferFundsAction $transferFunds, TransferFundsConsole $console)
    {
        parent::__construct();
        $this->action = $transferFunds;
        $this->action->attach($this);
        $this->console = $console;
    }

    /**
     * This command has three arguments: both, the sender and recipient IDs, and the amount
     * to be transferred
     */
    protected function configure()
    {
        $this
            ->setName('ewallet:transfer')
            ->setDescription('Transfer funds from a member to another')
            ->addArgument(
                'senderId',
                InputArgument::OPTIONAL,
                'The ID of the member making the transfer'
            )
            ->addArgument(
                'recipientId',
                InputArgument::OPTIONAL,
                'The ID of the member that will receive funds'
            )
            ->addArgument(
                'amount',
                InputArgument::OPTIONAL,
                'The amount to be transferred'
            )
        ;
    }

    /**
     * Allow the sender to enter the transfer information. This will result in 2 possible outcomes:
     *
     * - If invalid input is provided, show validation messages and stop.
     * - If input is correct, execute the transaction and notify the sender appropriately
     *
     * @throws \Ewallet\Memberships\InsufficientFunds If sender tries to transfer more than her
     * current balance
     * @throws \Ewallet\Memberships\InvalidTransfer If the amount to transfer is not greater than 0
     * @throws \Ewallet\Memberships\UnknownMember If either the sender or recipient are unknown
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->action->transfer(TransferFundsInput::from($input->getArguments()));

        return $this->exitCode;
    }

    public function respondToTransferCompleted(TransferFundsSummary $summary): void
    {
        $this->console->printSummary($summary);
        $this->exitCode = self::SUCCESS;
    }

    public function respondToInvalidInput(InputValidator $input): void
    {
        $this->console->printError($input->errors(), 'FUNDS-422-001');
        $this->exitCode = self::ERROR;
    }

    public function respondToUnknownMember(UnknownMember $exception): void
    {
        $this->console->printError([$exception->getMessage()], 'FUNDS-400-001');
        $this->exitCode = self::ERROR;
    }

    public function respondToInsufficientFunds(InsufficientFunds $exception): void
    {
        $this->console->printError([$exception->getMessage()], 'FUNDS-400-002');
        $this->exitCode = self::ERROR;
    }
}
