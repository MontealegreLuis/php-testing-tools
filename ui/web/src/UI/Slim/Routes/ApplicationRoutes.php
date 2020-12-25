<?php declare(strict_types=1);
/**
 * PHP version 7.4
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */

namespace UI\Slim\Routes;

use Framework\Slim\RoutesProvider;
use Slim\App;
use UI\Slim\Controllers\RedirectToTransferFormController;
use UI\Slim\Controllers\ShowTransferFormController;
use UI\Slim\Controllers\TransferFundsController;
use UI\Slim\Controllers\TransferFundsValidationMiddleware;

final class ApplicationRoutes implements RoutesProvider
{
    public function addRoutes(App $router): void
    {
        $router->get('/', RedirectToTransferFormController::class)->setName('ewallet_home');
        $router->get('/transfer-form', ShowTransferFormController::class)->setName('transfer_form');
        $router
            ->post('/transfer-funds', TransferFundsController::class)
            ->add(TransferFundsValidationMiddleware::class)
            ->setName('transfer_funds');
    }
}
