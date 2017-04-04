<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\SymfonyConsole\Commands;

use Ewallet\Memberships\{Member, MemberFormatter};
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Ewallet\ManageWallet\{TransferFundsAction, TransferFundsConsole, TransferFundsConsoleResponder, TransferFunds};
use Ewallet\Zf2\InputFilter\{Filters\TransferFundsFilter, TransferFundsInputFilter};
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Question\Question;

class TransferFundsCommandTest extends TestCase
{
    use ProvidesDoctrineSetup;

    /** @test */
    function it_transfers_funds_between_members()
    {
        $this
            ->question
            ->ask($this->input, $this->output, Argument::type(Question::class))
            ->willReturn('LMN', 5)
        ;

        $statusCode = $this->command->run($this->input, $this->output);

        $this->assertEquals($success = 0, $statusCode);
        $this->assertRegExp(
            '/Transfer completed successfully!/',
            $this->output->fetch()
        );
    }

    /** @before */
    public function configureCommand(): void
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../../../config.php');
        (new ThreeMembersWithSameBalanceFixture($this->_entityManager()))->load();

        /** @var \Ewallet\Memberships\MembersRepository $members */
        $members = $this->_repositoryForEntity(Member::class);
        $useCase = new TransferFunds($members);

        $this->input = new ArrayInput([]);
        $this->output = new BufferedOutput();
        $this->question = $this->prophesize(QuestionHelper::class);

        $this->command = new TransferFundsCommand(
            new TransferFundsAction(
                new TransferFundsConsoleResponder(
                    $this->input,
                    $members,
                    new TransferFundsConsole(
                        $this->input,
                        $this->output,
                        $this->question->reveal(),
                        new MemberFormatter()
                    )
                ),
                $useCase
            ),
            new TransferFundsInputFilter(new TransferFundsFilter(), $members)
        );
    }

    /** @var TransferFundsCommand Subject under test */
    private $command;

    /** @var QuestionHelper */
    private $question;

    /** @var ArrayInput */
    private $input;

    /** @var BufferedOutput */
    private $output;
}
