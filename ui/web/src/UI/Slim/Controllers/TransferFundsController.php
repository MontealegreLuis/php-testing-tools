<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\Controllers;

use Application\DomainException;
use Application\Templating\TemplateEngine;
use Ewallet\ManageWallet\TransferFunds\TransferFundsAction;
use Ewallet\ManageWallet\TransferFunds\TransferFundsInput;
use Ewallet\Memberships\InsufficientFunds;
use Ewallet\Memberships\InvalidTransfer;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\MembersWebRepository;
use Ewallet\Memberships\UnknownMember;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UI\Slim\ResponseFactory;

final class TransferFundsController implements RequestHandlerInterface
{
    private TransferFundsAction $action;

    private TemplateEngine $template;

    private ResponseFactory $response;

    private MembersWebRepository $members;

    public function __construct(
        TransferFundsAction $action,
        TemplateEngine $template,
        MembersWebRepository $members,
        ResponseFactory $response
    ) {
        $this->action = $action;
        $this->template = $template;
        $this->members = $members;
        $this->response = $response;
    }

    /**
     * Perform the transfer
     *
     * @throws InsufficientFunds If the sender does not have sufficient funds
     * @throws InvalidTransfer If the sender tries to transfer a negative amount
     * @throws UnknownMember If either the sender or the recipient are unknown
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $summary = $this->action->transfer(new TransferFundsInput((array) $request->getParsedBody()));
        } catch (DomainException $exception) {
            return $this->respondToDomainException($exception);
        }

        $recipients = $this->members->excluding($summary->senderId());
        $html = $this->template->render('member/transfer-funds.html', [
            'summary' => $summary,
            'senderId' => $summary->senderId(),
            'recipientId' => $summary->recipientId(),
            'recipients' => $recipients,
        ]);

        return $this->response->html($html);
    }

    public function respondToDomainException(DomainException $exception): ResponseInterface
    {
        $senderId = new MemberId('ABC');
        $recipients = $this->members->excluding($senderId);
        $html = $this->template->render('member/transfer-funds.html', [
            'senderId' => $senderId,
            'recipients' => $recipients,
            'errors' => ['senderId' => $exception->getMessage()],
        ]);

        return $this->response->html($html);
    }
}
