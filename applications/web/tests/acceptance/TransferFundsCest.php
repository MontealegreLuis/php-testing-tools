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
        $this->_setUpDoctrine(require __DIR__ . '/../../config.tests.php');
        $fixture = new ThreeMembersWithSameBalanceFixture($this->entityManager);
        $fixture->load();
    }

    public function tryToTransferFundsBetweenMembers(AcceptanceTester $I)
    {
        $I->am('ewallet member');
        $I->wantTo('pay a debt');
        $I->lookForwardTo('transfer funds to my friend');

        $I->amOnTransferFundsPage();
        $I->enterTransferInformation('Luis Montealegre', 5);
        $I->requestTransfer();

        $I->seeTransferCompletedConfirmation();
    }
}
