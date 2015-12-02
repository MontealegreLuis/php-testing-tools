<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\SymfonyConsole;

use Ewallet\Accounts\Identifier;
use Ewallet\Accounts\MemberInformation;
use Ewallet\Wallet\TransferFundsResponse;
use EwalletModule\Bridges\EasyForms\MembersConfiguration;
use EwalletModule\Responders\TransferFundsResponder;
use EwalletModule\View\MemberFormatter;
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

    /** @var MembersConfiguration */
    private $configuration;

    /** @var MemberFormatter */
    private $formatter;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param QuestionHelper $question
     * @param MembersConfiguration $configuration
     * @param MemberFormatter $formatter
     */
    public function __construct(
        InputInterface $input,
        OutputInterface $output,
        QuestionHelper $question,
        MembersConfiguration $configuration,
        MemberFormatter $formatter
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->question = $question;
        $this->configuration = $configuration;
        $this->formatter = $formatter;
    }

    /**
     * @param array $messages
     * @param array $values
     * @param string $fromMemberId
     */
    public function respondToInvalidTransferInput(
        array $messages,
        array $values,
        $fromMemberId
    ) {
        $this->output->writeln('<comment>Please fix the following errors</comment>');

        array_map(function (array $messages) {
            $message = implode(', ', $messages);
            $this->output->writeln("<error>{$message}</error>");
        }, $messages);

        $this->output->writeln('<info>Try again please.</info>');
    }

    /**
     * @param Identifier $fromMemberId
     */
    public function respondToEnterTransferInformation(Identifier $fromMemberId)
    {
        $members = $this->configuration->getMembersChoicesExcluding($fromMemberId);
        $table = new Table($this->output);
        $table
            ->setHeaders(['ID', 'Name', 'Balance'])
            ->setRows(array_map(function (MemberInformation $member) {
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
     * @param TransferFundsResponse $response
     */
    public function respondToTransferCompleted(TransferFundsResponse $response)
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
        $this->output->writeln("{$this->formatter->formatMember($forMember)}");
    }
}
