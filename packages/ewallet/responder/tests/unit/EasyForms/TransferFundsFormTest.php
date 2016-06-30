<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\EasyForms;

use Ewallet\Accounts\MemberId;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsFormTest extends TestCase
{
    /** @test */
    function it_populates_the_form_with_the_transfer_values()
    {
        $form = new TransferFundsForm();
        $form->submit([
            'senderId' => 'abc',
            'recipientId' => 'xyz',
            'amount' => 100,
        ]);

        $this->assertEquals([
            'senderId' => 'abc',
            'recipientId' => 'xyz',
            'amount' => ['amount' => 100, 'currency' => 'MXN'],
        ], $form->values());
    }

    /** @test */
    function it_creates_the_view_elements_to_make_the_transfer()
    {
        $form = new TransferFundsForm();
        $view = $form->buildView();

        $this->assertCount(3, $view);
        $this->assertTrue($view->offsetExists('senderId'));
        $this->assertTrue($view->offsetExists('recipientId'));
        $this->assertTrue($view->offsetExists('amount'));
    }

    /** @test */
    function it_initializes_the_member_id_making_the_transfer()
    {
        $form = new TransferFundsForm();
        $senderId = MemberId::with('abc');
        $configuration = Mockery::mock(MembersConfiguration::class);
        $configuration
            ->shouldReceive('getMembersChoicesExcluding')
            ->once()
            ->withAnyArgs()
            ->andReturn([])
        ;

        $form->configure($configuration, $senderId);
        $view = $form->buildView();

        $this->assertEquals($senderId, $view->senderId->value);
    }

    /** @test */
    function it_excludes_from_choices_the_member_making_the_transfer()
    {
        $form = new TransferFundsForm();
        $senderId = MemberId::with('abc');
        $configuration = Mockery::mock(MembersConfiguration::class);
        $configuration
            ->shouldReceive('getMembersChoicesExcluding')
            ->once()
            ->with($senderId)
            ->andReturn([
                'lmn' => null,
                'xyz' => null,
            ])
        ;

        $form->configure($configuration, $senderId);
        $view = $form->buildView();

        $this->assertCount(2, $view->recipientId->choices);
        $this->assertArrayNotHasKey('abc', $view->recipientId->choices);
    }
}
