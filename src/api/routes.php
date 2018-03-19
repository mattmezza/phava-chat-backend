<?php
declare(strict_types=1);

use Slim\Http\Request;
use Slim\Http\Response;

use Chat\API\MessageController;
use Chat\API\AuthMiddleware;

$app->group('/messages', function() {
  $this->get('', MessageController::class . ":getMessages");
  $this->post('/send', MessageController::class . ":sendMessage");
})->add(new AuthMiddleware());