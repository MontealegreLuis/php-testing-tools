<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\ManageWallet\TransferFunds;

use Application\Actions\InputValidator;
use Ewallet\Memberships\MemberId;
use Money\Money;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Information needed to make a transfer
 */
final class TransferFundsInput extends InputValidator
{
    /**
     * @Assert\NotBlank(message="Sender ID cannot be blank")
     */
    private $senderId;

    /**
     * @Assert\NotBlank(message="Recipient ID cannot be blank")
     */
    private $recipientId;

    /**
     * @Assert\Type(type="numeric",message="Transfer amount must be a number, '{{ value }}' found")
     * @Assert\GreaterThan(
     *     value=0,
     *     message="Transferred amount must be greater than {{ compared_value }}, '{{ value }}' found"
     * )
     */
    private $amount;

    public static function from(array $validInput): TransferFundsInput
    {
        return new self($validInput);
    }

    public function senderId(): MemberId
    {
        return MemberId::withIdentity($this->senderId);
    }

    public function recipientId(): MemberId
    {
        return MemberId::withIdentity($this->recipientId);
    }

    public function amount(): Money
    {
        return Money::MXN($this->amount * 100);
    }

    /**
     * TransferFundsInformation constructor.
     */
    public function __construct(array $values)
    {
        parent::__construct($values);
        $this->senderId = trim($values['senderId'] ?? '');
        $this->recipientId = trim($values['recipientId'] ?? '');
        $this->amount = $values['amount'] ?? 0;
    }
}
