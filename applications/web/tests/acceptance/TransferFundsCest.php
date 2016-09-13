<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;

class TransferFundsCest
{
    use ProvidesDoctrineSetup;

    public function _before()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../config.php');
        $fixture = new ThreeMembersWithSameBalanceFixture($this->_entityManager());
        $fixture->load();
    }

    public function tryToTransferFundsToARecipient(AcceptanceTester $I)
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
