<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console\Commands;

use Adapters\Laminas\Application\InputValidation\LaminasInputFilter;
use Adapters\Symfony\Ewallet\ManageWallet\TransferFunds\TransferFundsValues;
use Application\DomainException;
use Application\InputValidation\InputValidator;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TransferFundsCommand extends Command
{
    private const ERROR = 1;

    /** @var TransferFundsAction */
    private $action;

    /** @var TransferFundsConsole */
    private $console;

    /** @var InputValidator */
    private $validator;

    public function __construct(
        TransferFundsAction $transferFunds,
        TransferFundsConsole $console,
        InputValidator $validator
    ) {
        parent::__construct();
        $this->action = $transferFunds;
        $this->console = $console;
        $this->validator = $validator;
    }

    /**
     * This command has three arguments: both, the sender and recipient IDs, and the amount
     * to be transferred
     */
    protected function configure(): void
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
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $values = new TransferFundsValues(new LaminasInputFilter($input->getArguments()));
        $result = $this->validator->validate($values);
        if (! $result->isValid()) {
            $this->console->printError($result->errors(), 'invalid-input');
            return self::ERROR;
        }

        try {
            $summary = $this->action->transfer(new TransferFundsInput($values->values()));
            $this->console->printSummary($summary);
        } catch (DomainException $exception) {
            $this->console->printError([$exception->getMessage()], 'cannot-complete-transfer');
            return self::ERROR;
        }

        return self::SUCCESS;
    }
}
