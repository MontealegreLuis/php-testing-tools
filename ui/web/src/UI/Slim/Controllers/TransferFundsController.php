<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\Controllers;

use Application\Actions\InputValidator;
use Application\Templating\TemplateEngine;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsInput;
use Ewallet\ManageWallet\TransferFunds\TransferFundsResponder;
use Ewallet\ManageWallet\TransferFunds\TransferFundsSummary;
use Ewallet\Memberships\InsufficientFunds;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\MembersWebRepository;
use Ewallet\Memberships\UnknownMember;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class TransferFundsController implements TransferFundsResponder
{
    /** @var TransferFundsAction */
    private $action;

    /** @var TemplateEngine */
    private $template;

    /** @var ResponseInterface */
    private $response;

    /** @var MembersWebRepository */
    private $members;

    public function __construct(TransferFundsAction $action, TemplateEngine $template, MembersWebRepository $members)
    {
        $this->action = $action;
        $this->action->attach($this);
        $this->template = $template;
        $this->members = $members;
    }

    /**
     * Perform the transfer
     *
     * @throws \Ewallet\Memberships\InsufficientFunds If the sender does not
     * have sufficient funds
     * @throws \Ewallet\Memberships\InvalidTransfer If the sender tries to
     * transfer a negative amount
     * @throws \Ewallet\Memberships\UnknownMember If either the sender or the
     * recipient are unknown
     */
    public function transfer(Request $request): ResponseInterface
    {
        $this->action->transfer(TransferFundsInput::from((array)$request->getParsedBody()));

        return $this->response;
    }

    public function respondToInvalidInput(InputValidator $input): void
    {
        $recipients = $this->members->excluding(new MemberId($input->values()['senderId']));
        $html = $this->template->render('member/transfer-funds.html', [
            'senderId' => $input->values()['senderId'],
            'recipientId' => new MemberId($input->values()['recipientId']),
            'recipients' => $recipients,
            'amount' => $input->values()['amount'],
            'errors' => $input->errors(),
        ]);

        $response = new Response();
        $response->getBody()->write($html);
        $this->response = $response;
    }

    public function respondToTransferCompleted(TransferFundsSummary $summary): void
    {
        $recipients = $this->members->excluding($summary->senderId());
        $html = $this->template->render('member/transfer-funds.html', [
            'summary' => $summary,
            'senderId' => $summary->senderId(),
            'recipientId' => $summary->recipientId(),
            'recipients' => $recipients,
        ]);

        $response = new Response();
        $response->getBody()->write($html);
        $this->response = $response;
    }

    public function respondToUnknownMember(UnknownMember $exception): void
    {
        $senderId = new MemberId('ABC');
        $recipients = $this->members->excluding($senderId);
        $html = $this->template->render('member/transfer-funds.html', [
            'senderId' => $senderId,
            'recipients' => $recipients,
            'errors' => ['senderId' => $exception->getMessage()],
        ]);

        $response = new Response();
        $response->getBody()->write($html);
        $this->response = $response;
    }

    public function respondToInsufficientFunds(InsufficientFunds $exception): void
    {
        $senderId = new MemberId('ABC');
        $recipients = $this->members->excluding($senderId);
        $html = $this->template->render('member/transfer-funds.html', [
            'senderId' => $senderId,
            'recipients' => $recipients,
            'errors' => ['amount' => $exception->getMessage()],
        ]);

        $response = new Response();
        $response->getBody()->write($html);
        $this->response = $response;
    }
}
