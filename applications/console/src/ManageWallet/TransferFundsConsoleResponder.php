<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet;

use Ewallet\Memberships\{MemberId, Member, MemberFormatter, MemberInformation, MembersRepository};
use Symfony\Component\Console\{
    Helper\QuestionHelper,
    Helper\Table,
    Input\InputInterface,
    Output\OutputInterface,
    Question\Question
};

class TransferFundsConsoleResponder implements TransferFundsResponder
{
    /** @var InputInterface */
    private $input;

    /** @var OutputInterface */
    private $output;

    /** @var QuestionHelper */
    private $question;

    /** @var MembersRepository */
    private $members;

    /** @var MemberFormatter */
    private $formatter;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $question
     * @param MembersRepository $members
     * @param MemberFormatter $formatter
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $question,
        MembersRepository $members,
        MemberFormatter $formatter
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->question = $question;
        $this->members = $members;
        $this->formatter = $formatter;
    }

    /**
     * @param array $messages
     * @param array $values
     */
    public function respondToInvalidTransferInput(array $messages, array $values)
    {
        $this->output->writeln('<comment>Please fix the following errors</comment>');

        array_map(function (array $messages) {
            $message = implode(', ', $messages);
            $this->output->writeln("<error>{$message}</error>");
        }, $messages);

        $this->output->writeln('<info>Try again please.</info>');
    }

    /**
     * @param MemberId $fromMemberId
     */
    public function respondToEnterTransferInformation(MemberId $fromMemberId)
    {
        $members = $this->members->excluding($fromMemberId);
        $table = new Table($this->output);
        $table
            ->setHeaders(['ID', 'Name', 'Balance'])
            ->setRows(array_map(function (Member $member) {
                    $member = $member->information();
                    return [
                        $member->id(),
                        $member->name(),
                        $this->formatter->formatMoney($member->accountBalance())
                    ];
                }, $members)
            )
        ;
        $table->render();
        $recipientId = $this->question->ask(
            $this->input,
            $this->output,
            new Question('Transfer to ID: ')
        );

        $amount = $this->question->ask(
            $this->input,
            $this->output,
            new Question('Amount to transfer: ')
        );

        $this->input->setArgument('recipientId', $recipientId);
        $this->input->setArgument('amount', $amount);
    }

    /**
     * @param TransferFundsSummary $summary
     */
    public function respondToTransferCompleted(TransferFundsSummary $summary)
    {
        $this->output->writeln('<info>Transfer completed successfully!</info>');
        $this->printStatement($summary->sender());
        $this->printStatement($summary->recipient());
    }

    /**
     * @param MemberInformation $forMember
     */
    private function printStatement(MemberInformation $forMember)
    {
        $this->output->writeln("{$this->formatter->formatMember($forMember)}");
    }
}
