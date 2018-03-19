<?php
declare(strict_types=1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

$container = require "../bootstrap/container.php";
$app = new \Slim\App($container);

require '../src/api/routes.php';

$app->run();