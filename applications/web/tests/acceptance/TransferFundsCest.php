<?php
/**
 * PHP version 7.0
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Ewallet\Accounts\Member;
use Ewallet\Alice\ThreeMembersWithSameBalanceFixture;
use Ewallet\Doctrine2\ProvidesDoctrineSetup;
use Page\TransferFundsPage;
use Nelmio\Alice\Fixtures;

class TransferFundsCest
{
    use ProvidesDoctrineSetup;

    public function _before()
    {
        $this->_setUpDoctrine(require __DIR__ . '/../../config.tests.php');
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . Member::class)
            ->execute()
        ;
        $fixture = new ThreeMembersWithSameBalanceFixture($this->entityManager);
        $fixture->load();
    }

    public function tryToTransferFundsBetweenMembers(AcceptanceTester $I)
    {
        $I->am('ewallet member');
        $I->wantTo('pay a debt');
        $I->lookForwardTo('transfer funds to my friend');

        $I->amOnPage(TransferFundsPage::$formPage);
        $I->selectOption(TransferFundsPage::$toMember, 'Luis Montealegre');
        $I->fillField(TransferFundsPage::$amount, 5);
        $I->click(TransferFundsPage::$transfer);

        $I->seeCurrentUrlMatches('/' . TransferFundsPage::$transferPage . '/');
        $I->seeElement(TransferFundsPage::$successMessage);
    }
}
