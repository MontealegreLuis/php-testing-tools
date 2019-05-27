<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\Controllers;

use Ewallet\Memberships\MemberId;
use Ewallet\Memberships\MembersWebRepository;
use Application\Templating\TemplateEngine;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

class ShowTransferFormController
{
    /** @var MembersWebRepository */
    private $members;

    /** @var TemplateEngine */
    private $template;

    public function __construct(MembersWebRepository $members, TemplateEngine $template)
    {
        $this->members = $members;
        $this->template = $template;
    }

    /**
     * Show the form to transfer funds between members
     */
    public function enterTransferInformation(): ResponseInterface
    {
        $senderId = new MemberId('ABC');

        $html = $this->template->render('member/transfer-funds.html', [
            'senderId' => $senderId,
            'recipients' => $this->members->excluding($senderId),
        ]);

        $response = new Response();
        $response->getBody()->write($html);
        return $response;
    }
}
