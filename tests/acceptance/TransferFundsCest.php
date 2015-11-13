<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
use Page\TransferFundsPage;
use TestHelpers\Bridges\ProvidesDoctrineSetup;

class TransferFundsCest
{
    use ProvidesDoctrineSetup;

    public function _before()
    {
        $this->_setUpDoctrine();
        $this
            ->entityManager
            ->createQuery('DELETE FROM ' . \Ewallet\Accounts\Member::class)
            ->execute()
        ;
        \Nelmio\Alice\Fixtures::load(
            __DIR__ . '/../_data/fixtures/members.yml', $this->entityManager
        );
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
