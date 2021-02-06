<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

use _generated\AcceptanceTesterActions;
use Codeception\Actor;
use Page\TransferFundsPage;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
final class AcceptanceTester extends Actor
{
    use AcceptanceTesterActions;

    public function amOnTransferFundsPage(): void
    {
        $this->amOnPage(TransferFundsPage::$inputTransferInformationPage);
    }

    /**
     * @param string $name Recipient's name
     * @param int $amount Amount in MXN
     */
    public function enterTransferInformation(string $name, int $amount): void
    {
        $this->selectOption(TransferFundsPage::$recipients, $name);
        $this->fillField(TransferFundsPage::$amount, $amount);
    }

    public function makeTheTransfer(): void
    {
        $this->click(TransferFundsPage::$transferButton);
    }

    public function seeTransferCompletedConfirmation(): void
    {
        $this->seeCurrentUrlMatches('/' . TransferFundsPage::$transferCompletedPage . '/');
        $this->seeElement(TransferFundsPage::$successMessage);
    }
}
