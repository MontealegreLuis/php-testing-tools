<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\{Member, MemberFormatter, MemberInformation};
use Symfony\Component\Console\Helper\{QuestionHelper, Table};
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class TransferFundsConsole
{
    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;

    /** @var QuestionHelper */
    private $question;

    /** @var MemberFormatter */
    private $formatter;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $question
     * @param MemberFormatter $formatter
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $question,
        MemberFormatter $formatter
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->question = $question;
        $this->formatter = $formatter;
    }

    /**
     * Show validation messages to the sender
     *
     * @param array $messages
     */
    public function printError(array $messages)
    {
        $this->output->writeln('<comment>Please fix the following errors</comment>');

        array_map(function (array $messages) {
            $message = implode(', ', $messages);
            $this->output->writeln("<error>{$message}</error>");
        }, $messages);

        $this->output->writeln('<info>Try again please.</info>');
    }

    /**
     * Show the list of recipients to the sender
     *
     * @param array $recipients
     */
    public function printRecipients(array $recipients)
    {
        $table = new Table($this->output);
        $table
            ->setHeaders(['ID', 'Name', 'Balance'])
            ->setRows(array_map(function (Member $recipient) {
                $recipient = $recipient->information();
                return [
                    $recipient->id(),
                    $recipient->name(),
                    $this->formatter->formatMoney($recipient->accountBalance())
                ];
            }, $recipients))
        ;
        $table->render();
    }

    /**
     * Show the transaction summary to the sender
     *
     * @param TransferFundsSummary $summary
     */
    public function printSummary(TransferFundsSummary $summary)
    {
        $this->output->writeln('<info>Transfer completed successfully!</info>');
        $this->printStatementFor($summary->sender());
        $this->printStatementFor($summary->recipient());
    }

    /**
     * @param MemberInformation $member
     */
    private function printStatementFor(MemberInformation $member)
    {
        $this->output->writeln("{$this->formatter->formatMember($member)}");
    }

    /**
     * @return string
     */
    public function promptRecipientId(): string
    {
        return $this->question->ask(
            $this->input,
            $this->output,
            new Question('Transfer to ID: ')
        );
    }

    /**
     * @return string
     */
    public function promptAmountToTransfer(): string
    {
        return $this->question->ask(
            $this->input,
            $this->output,
            new Question('Amount to transfer: ')
        );
    }
}
