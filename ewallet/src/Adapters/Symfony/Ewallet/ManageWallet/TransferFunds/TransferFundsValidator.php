<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace Adapters\Symfony\Ewallet\ManageWallet\TransferFunds;

use Adapters\Symfony\Application\Actions\ConstraintValidator;
use Symfony\Component\Validator\Constraints as Assert;

class TransferFundsValidator extends ConstraintValidator
{
    /**
     * @Assert\NotBlank(message="Sender ID cannot be blank")
     * @var string
     */
    protected $senderId;

    /**
     * @Assert\NotBlank(message="Recipient ID cannot be blank")
     * @var string
     */
    protected $recipientId;

    /**
     * @Assert\Type(type="numeric",message="Transfer amount must be a number, '{{ value }}' found")
     * @Assert\GreaterThan(
     *     value=0,
     *     message="Transferred amount must be greater than {{ compared_value }}, '{{ value }}' found"
     * )
     * @var int
     */
    protected $amount;

    /** @param mixed[] $input */
    public function __construct(array $input)
    {
        parent::__construct();
        $this->senderId = trim($input['senderId'] ?? '');
        $this->recipientId = trim($input['recipientId'] ?? '');
        $this->amount = $input['amount'] ?? 0;
    }
}
