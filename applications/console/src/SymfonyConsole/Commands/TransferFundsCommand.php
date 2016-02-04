<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\Accounts\MemberId;
use Ewallet\Actions\TransferFundsRequest;
use Ewallet\Actions\TransferFundsAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransferFundsCommand extends Command
{
    /** @var TransferFundsAction */
    private $action;

    /** @var TransferFundsRequest */
    private $request;

    /**
     * @param TransferFundsAction $transferFunds
     * @param TransferFundsRequest $request
     */
    public function __construct(
        TransferFundsAction $transferFunds,
        TransferFundsRequest $request
    ) {
        parent::__construct();
        $this->action = $transferFunds;
        $this->request = $request;
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this
            ->setName('ewallet:transfer')
            ->setDescription('Transfer funds from a member to another')
            ->addArgument(
                'fromMemberId',
                InputArgument::OPTIONAL,
                'The ID of the member making the transfer'
            )
            ->addArgument(
                'toMemberId',
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
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fromMemberId = MemberId::with('ABC');
        $input->setArgument('fromMemberId', (string) $fromMemberId);
        $this->action->enterTransferInformation($fromMemberId);
        $this->request->populate($input->getArguments());
        $this->action->transfer($this->request);
    }
}
