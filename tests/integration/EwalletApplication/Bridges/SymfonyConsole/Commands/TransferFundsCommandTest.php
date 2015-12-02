<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletApplication\Bridges\SymfonyConsole\Commands;

use Ewallet\Accounts\Member;
use Ewallet\Wallet\TransferFunds;
use EwalletModule\Actions\TransferFundsAction;
use EwalletModule\Bridges\EasyForms\MembersConfiguration;
use EwalletModule\Bridges\SymfonyConsole\TransferFundsConsoleResponder;
use EwalletModule\Bridges\Zf2\InputFilter\Filters\TransferFundsFilter;
use EwalletModule\Bridges\Zf2\InputFilter\TransferFundsInputFilterRequest;
use EwalletModule\View\MemberFormatter;
use Fakes\Symfony\Console\FakeQuestionHelper;
use Mockery;
use Nelmio\Alice\Fixtures;
use PHPUnit_Framework_TestCase as TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use TestHelpers\Bridges\ProvidesDoctrineSetup;

class TransferFundsCommandTest extends TestCase
{
    use ProvidesDoctrineSetup;

    public function setUp()
    {
        $this->_setUpDoctrine();
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
    }

    /** @test */
    function it_should_transfer_funds_between_members()
    {
        Fixtures::load(
            __DIR__ . '/../../../../../_data/fixtures/members.yml',
            $this->entityManager
        );
        $useCase = new TransferFunds(
            $members = $this->entityManager->getRepository(Member::class)
        );
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $question =  new FakeQuestionHelper();
        $action = new TransferFundsAction(
            new TransferFundsConsoleResponder(
                $input,
                $output,
                $question,
                $configuration = new MembersConfiguration($members),
                new MemberFormatter()
            ),
            $useCase
        );
        $command = new TransferFundsCommand(
            $action,
            new TransferFundsInputFilterRequest(
                new TransferFundsFilter(),
                $configuration
            )
        );

        $statusCode = $command->run($input, $output);

        $this->assertEquals($success = 0, $statusCode);
        $this->assertRegexp(
            '/Transfer completed successfully!/',
            $output->fetch()
        );
    }
}
