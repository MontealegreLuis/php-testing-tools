<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms;

use Ewallet\Memberships\MemberId;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsFormTest extends TestCase
{
    /** @test */
    function it_populates_the_form_with_the_transfer_values()
    {
        $this->form->submit([
            'senderId' => 'abc',
            'recipientId' => 'xyz',
            'amount' => 100,
        ]);

        $this->assertEquals([
            'senderId' => 'abc',
            'recipientId' => 'xyz',
            'amount' => ['amount' => 100, 'currency' => 'MXN'],
        ], $this->form->values());
    }

    /** @test */
    function it_creates_the_view_elements_to_make_the_transfer()
    {
        $view = $this->form->buildView();

        $this->assertCount(3, $view);
        $this->assertTrue($view->offsetExists('senderId'));
        $this->assertTrue($view->offsetExists('recipientId'));
        $this->assertTrue($view->offsetExists('amount'));
    }

    /** @test */
    function it_initializes_the_member_id_making_the_transfer()
    {
        $this->form->configure($this->configuration->reveal(), $this->senderId);

        $view = $this->form->buildView();

        $this->assertEquals($this->senderId, $view->senderId->value);
    }

    /** @test */
    function it_excludes_from_choices_the_member_making_the_transfer()
    {
        $this->form->configure($this->configuration->reveal(), $this->senderId);

        $recipientIds = $this->form->buildView()->recipientId->choices;

        $this->assertCount(2, $recipientIds);
        $this->assertArrayNotHasKey('abc', $recipientIds);
        $this->assertEquals($this->validRecipients, $recipientIds);
    }

    /** @before */
    public function configureForm()
    {
        $this->form = new TransferFundsForm();
        $this->senderId = MemberId::withIdentity('abc');
        $this->configuration = $this->prophesize(MembersConfiguration::class);
        $this
            ->configuration
            ->getMembersChoicesExcluding($this->senderId)
            ->willReturn($this->validRecipients)
        ;
    }

    /** @var TransferFundsForm */
    private $form;

    /** @var MembersConfiguration */
    private $configuration;

    /** @var MemberId */
    private $senderId;

    /** @var array */
    private $validRecipients = [
        'lmn' => null,
        'xyz' => null,
    ];
}
