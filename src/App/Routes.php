<?php
declare(strict_types=1);

use App\Controller\User\CreateController;
use App\Controller\User\GetAllController;
use App\Controller\User\GetOneController;
use App\Controller\User\LoginController;
use App\Controller\User\UpdateController;
use App\Middleware\AuthMiddleware;
use App\Middleware\GetUserMiddleware;
use Slim\App;

/** @var App $app */

$app->get('/', 'App\Controller\DefaultController:getHelp');
$app->get('/status', 'App\Controller\DefaultController:getStatus');
$app->post('/login', LoginController::class);

$app->group('/api/v1', function () use ($app): void {
    $app->group('/users', function () use ($app): void {
        $app->get('', GetAllController::class)->add(AuthMiddleware::class);

        $app->post('', CreateController::class);

        $app->get('/{id}', GetOneController::class)
            ->add(GetUserMiddleware::class)
            ->add(AuthMiddleware::class);

        $app->put('/{id}', UpdateController::class)
            ->add(GetUserMiddleware::class)
            ->add(AuthMiddleware::class);
    });
});
