<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace DataBuilders\Ewallet\Memberships;

use DataBuilders\Random;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\TransferWasMade;
use Money\Money;

final class TransferWasMadeBuilder
{
    private ?string $senderId = null;

    private ?int $amount = null;

    private ?string $recipientId = null;

    public function build(): TransferWasMade
    {
        return new TransferWasMade(
            new MemberId($this->senderId ?? Random::uuid()),
            Money::MXN($this->amount ?? Random::cents()),
            new MemberId($this->recipientId ?? Random::uuid())
        );
    }
}
