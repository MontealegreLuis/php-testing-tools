<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use Alice\ThreeMembersWithSameBalanceFixture;
use Doctrine\DataStorageSetup;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\ORMException;
use Doctrine\WithDatabaseSetup;

final class TransferFundsCest
{
    use WithDatabaseSetup;

    /**
     * @throws Exception
     * @throws ORMException
     */
    public function _before(): void
    {
        $this->_setupDatabaseSchema(new \SplFileInfo(__DIR__ . '/../../'));
        $fixture = new ThreeMembersWithSameBalanceFixture($this->setup->entityManager());
        $fixture->load();
    }

    public function tryToTransferFundsToARecipient(AcceptanceTester $I): void
    {
        $I->am('sender');
        $I->wantTo('share my funds with one of my recipients');
        $I->lookForwardTo('transfer funds to her');

        $I->amOnTransferFundsPage();
        $I->enterTransferInformation('Luis Montealegre', 5);
        $I->makeTheTransfer();

        $I->seeTransferCompletedConfirmation();
    }
}
