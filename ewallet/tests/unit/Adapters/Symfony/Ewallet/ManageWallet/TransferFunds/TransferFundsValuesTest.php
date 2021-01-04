<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\Ewallet\ManageWallet\TransferFunds;

use Adapters\Laminas\Application\InputValidation\LaminasInputFilter;
use Adapters\Symfony\Application\InputValidation\ConstraintValidator;
use DataBuilders\Values;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ValidatorBuilder;

final class TransferFundsValuesTest extends TestCase
{
    /** @test */
    function it_does_not_pass_validation_if_no_input_is_present()
    {
        $values = new TransferFundsValues(new LaminasInputFilter([]));

        $result = $this->validator->validate($values);

        $this->assertFalse($result->isValid());
        $this->assertCount(3, $result->errors());
        $this->assertArrayHasKey('senderId', $result->errors());
        $this->assertArrayHasKey('recipientId', $result->errors());
        $this->assertArrayHasKey('amount', $result->errors());
    }

    /** @test */
    function it_does_not_pass_validation_if_ids_are_empty()
    {
        $values = Values::transferFundsValues([
            'senderId' => '  ',
            'recipientId' => '',
        ]);

        $result = $this->validator->validate($values);

        $this->assertFalse($result->isValid());
        $this->assertCount(2, $result->errors());
        $this->assertArrayHasKey('senderId', $result->errors());
        $this->assertArrayHasKey('recipientId', $result->errors());
    }

    /** @test */
    function it_does_not_pass_validation_if_amount_is_not_greater_than_zero()
    {
        $values = Values::transferFundsValues(([
            'amount' => 0,
        ]));

        $result = $this->validator->validate($values);

        $this->assertFalse($result->isValid());
        $this->assertCount(1, $result->errors());
        $this->assertArrayHasKey('amount', $result->errors());
    }

    /** @before */
    function let()
    {
        $this->validator = new ConstraintValidator((new ValidatorBuilder())->enableAnnotationMapping()->getValidator());
    }

    private ConstraintValidator $validator;
}
