<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\Memberships\MemberId;
use Ewallet\ManageWallet\{TransferFundsInput, TransferFundsAction};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;

class TransferFundsCommand extends Command
{
    /** @var TransferFundsAction */
    private $action;

    /** @var TransferFundsInput */
    private $input;

    public function __construct(
        TransferFundsAction $transferFunds,
        TransferFundsInput $input
    ) {
        parent::__construct();
        $this->action = $transferFunds;
        $this->input = $input;
    }

    /**
     * This command has three arguments: sender and recipient IDs an the amount
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

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $senderId = MemberId::withIdentity('ABC');
        $input->setArgument('senderId', $senderId->value());
        $this->action->enterTransferInformation($senderId);
        $this->input->populate($input->getArguments());
        $this->action->transfer($this->input);
    }
}
