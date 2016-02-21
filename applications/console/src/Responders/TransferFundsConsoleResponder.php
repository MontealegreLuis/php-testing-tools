<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Responders;

use Ewallet\Accounts\MemberId;
use Ewallet\Accounts\Member;
use Ewallet\Accounts\MemberInformation;
use Ewallet\Accounts\MembersRepository;
use Ewallet\Wallet\TransferFundsResult;
use Ewallet\Presenters\MemberFormatter;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

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
        $toMemberId = $this->question->ask(
            $this->input,
            $this->output,
            new Question('Transfer to ID: ')
        );

        $amount = $this->question->ask(
            $this->input,
            $this->output,
            new Question('Amount to transfer: ')
        );

        $this->input->setArgument('toMemberId', $toMemberId);
        $this->input->setArgument('amount', $amount);
    }

    /**
     * @param TransferFundsResult $result
     */
    public function respondToTransferCompleted(TransferFundsResult $result)
    {
        $this->output->writeln('<info>Transfer completed successfully!</info>');
        $this->printStatement($result->fromMember());
        $this->printStatement($result->toMember());
    }

    /**
     * @param MemberInformation $forMember
     */
    private function printStatement(MemberInformation $forMember)
    {
        $this->output->writeln("{$this->formatter->formatMember($forMember)}");
    }
}
