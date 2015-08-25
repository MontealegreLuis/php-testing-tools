<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace EwalletModule\Bridges\EasyForms;

use Ewallet\Accounts\Identifier;
use Mockery;
use PHPUnit_Framework_TestCase as TestCase;

class TransferFundsFormTest extends TestCase
{
    /** @test */
    function it_should_populate_the_form_with_the_transfer_values()
    {
        $form = new TransferFundsForm();
        $form->submit([
            'fromMemberId' => 'abc',
            'toMemberId' => 'xyz',
            'amount' => 100,
        ]);

        $this->assertEquals([
            'fromMemberId' => 'abc',
            'toMemberId' => 'xyz',
            'amount' => ['amount' => 100, 'currency' => 'MXN'],
        ], $form->values());
    }

    /** @test */
    function it_should_create_the_view_elements_to_make_the_transfer()
    {
        $form = new TransferFundsForm();
        $view = $form->buildView();

        $this->assertCount(3, $view);
        $this->assertTrue($view->offsetExists('fromMemberId'));
        $this->assertTrue($view->offsetExists('toMemberId'));
        $this->assertTrue($view->offsetExists('amount'));
    }

    /** @test */
    function it_should_initialize_the_member_id_making_the_transfer()
    {
        $form = new TransferFundsForm();
        $fromMemberId = Identifier::fromString('abc');
        $configuration = Mockery::mock(MembersConfiguration::class);
        $configuration
            ->shouldReceive('getMembersChoicesExcluding')
            ->once()
            ->withAnyArgs()
            ->andReturn([])
        ;

        $form->configure($configuration, $fromMemberId);
        $view = $form->buildView();

        $this->assertEquals($fromMemberId, $view->fromMemberId->value);
    }

    /** @test */
    function it_should_exclude_from_choices_the_member_making_the_transfer()
    {
        $form = new TransferFundsForm();
        $fromMemberId = Identifier::fromString('abc');
        $configuration = Mockery::mock(MembersConfiguration::class);
        $configuration
            ->shouldReceive('getMembersChoicesExcluding')
            ->once()
            ->with($fromMemberId)
            ->andReturn([
                'lmn' => null,
                'xyz' => null,
            ])
        ;

        $form->configure($configuration, $fromMemberId);
        $view = $form->buildView();

        $this->assertCount(2, $view->toMemberId->choices);
        $this->assertArrayNotHasKey('abc', $view->toMemberId->choices);
    }
}
