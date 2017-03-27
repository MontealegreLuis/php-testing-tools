<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Ewallet\Zf2\InputFilter;

use Ewallet\Memberships\{MemberId, MembersRepository};
use Ewallet\ManageWallet\TransferFundsInput;
use Ewallet\Zf2\InputFilter\Filters\TransferFundsFilter;

class TransferFundsInputFilter implements TransferFundsInput
{
    /** @var TransferFundsFilter */
    private $filter;

    /** @var MembersRepository */
    private $members;

    public function __construct(TransferFundsFilter $filter, MembersRepository $members)
    {
        $this->filter = $filter;
        $this->members = $members;
    }

    public function populate(array $input): void
    {
        $this->filter->setData($input);
    }

    public function isValid(): bool
    {
        $senderId = $this->filter->getRawValue('senderId');
        if ($senderId) {
            $recipients = $this->members->excluding(MemberId::withIdentity($senderId));
            $this->filter->configure($recipients);
        }

        return $this->filter->isValid();
    }

    /**
     * @return string[]
     */
    public function errorMessages(): array
    {
        return $this->filter->getMessages();
    }

    public function values(): array
    {
        return $this->filter->getValues();
    }
}
