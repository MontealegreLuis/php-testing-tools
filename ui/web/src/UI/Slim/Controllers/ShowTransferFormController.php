<?php declare(strict_types=1);
/**
 * PHP version 7.2
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\Controllers;

use Application\Templating\TemplateEngine;
use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\MembersWebRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use UI\Slim\ResponseFactory;

final class ShowTransferFormController implements RequestHandlerInterface
{
    private ResponseFactory $response;

    private MembersWebRepository $members;

    private TemplateEngine $template;

    public function __construct(MembersWebRepository $members, TemplateEngine $template, ResponseFactory $response)
    {
        $this->members = $members;
        $this->template = $template;
        $this->response = $response;
    }

    /**
     * Show the form to transfer funds between members
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $senderId = new MemberId('ABC');

        $html = $this->template->render('member/transfer-funds.html', [
            'senderId' => $senderId,
            'recipients' => $this->members->excluding($senderId),
        ]);

        return $this->response->html($html);
    }
}
