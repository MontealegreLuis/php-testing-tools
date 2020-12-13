<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\Ewallet\ManageWallet\TransferFunds;

use Application\InputValidation\InputFilter;
use Application\InputValidation\InputValues;
use Symfony\Component\Validator\Constraints as Assert;

final class TransferFundsValues extends InputValues
{
    /**
     * @Assert\NotBlank(message="Sender ID cannot be blank")
     */
    protected ?string $senderId;

    /**
     * @Assert\NotBlank(message="Recipient ID cannot be blank")
     */
    protected ?string $recipientId;

    /**
     * @Assert\Type(type="numeric", message="Transfer amount must be a number, '{{ value }}' found")
     * @Assert\GreaterThan(
     *     value=0,
     *     message="Transferred amount must be greater than {{ compared_value }}, '{{ value }}' found"
     * )
     */
    protected float $amount;

    public function __construct(InputFilter $filter)
    {
        $this->senderId = $filter->trim('senderId');
        $this->recipientId = $filter->trim('recipientId');
        $this->amount = (float) $filter->float('amount', -1);
    }
}
