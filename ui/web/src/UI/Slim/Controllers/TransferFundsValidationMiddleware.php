<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\Controllers;

use Adapters\Laminas\Application\InputValidation\LaminasInputFilter;
use Adapters\Symfony\Ewallet\ManageWallet\TransferFunds\TransferFundsValues;
use Application\InputValidation\InputValidator;
use Application\Templating\TemplateEngine;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\MembersWebRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UI\Slim\ResponseFactory;

final class TransferFundsValidationMiddleware implements MiddlewareInterface
{
    private InputValidator $validator;

    private MembersWebRepository $members;

    private TemplateEngine $template;

    private ResponseFactory $response;

    public function __construct(
        InputValidator $validator,
        MembersWebRepository $members,
        TemplateEngine $template,
        ResponseFactory $response
    ) {
        $this->validator = $validator;
        $this->members = $members;
        $this->template = $template;
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $values = new TransferFundsValues(new LaminasInputFilter((array) $request->getParsedBody()));
        $result = $this->validator->validate($values);

        if ($result->isValid()) {
            return $handler->handle($request);
        }

        $input = $values->values();

        $recipients = $this->members->excluding(new MemberId($input['senderId']));
        $html = $this->template->render('member/transfer-funds.html', [
            'senderId' => $input['senderId'],
            'recipientId' => new MemberId($input['recipientId']),
            'recipients' => $recipients,
            'amount' => $input['amount'],
            'errors' => $result->errors(),
        ]);

        return $this->response->html($html);
    }
}
