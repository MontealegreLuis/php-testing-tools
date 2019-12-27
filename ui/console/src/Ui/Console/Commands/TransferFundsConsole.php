<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Ewallet\Ui\Console\Commands;

use Ewallet\ManageWallet\TransferFunds\TransferFundsSummary;
use Ewallet\Memberships\Member;
use Ewallet\Memberships\MemberFormatter;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interacts with a sender in a console application to transfer funds to a recipient
 */
class TransferFundsConsole
{
    /** @var OutputInterface */
    private $output;

    /** @var MemberFormatter */
    private $formatter;

    public function __construct(OutputInterface $output, MemberFormatter $formatter)
    {
        $this->output = $output;
        $this->formatter = $formatter;
    }

    /**
     * Show validation messages to the sender
     *
     * @param string[] $messages
     */
    public function printError(array $messages, string $errorCode): void
    {
        $this->output->writeln("<comment>[{$errorCode}] Please fix the following errors</comment>");

        array_map(function (string $message) {
            $this->output->writeln("<error>{$message}</error>");
        }, $messages);

        $this->output->writeln('<info>Try again please.</info>');
    }

    /**
     * Show the transaction summary to the sender
     */
    public function printSummary(TransferFundsSummary $summary): void
    {
        $this->output->writeln('<info>Transfer completed successfully!</info>');
        $this->printStatementFor($summary->sender());
        $this->printStatementFor($summary->recipient());
    }

    private function printStatementFor(Member $member): void
    {
        $this->output->writeln("{$this->formatter->formatMember($member)}");
    }
}
