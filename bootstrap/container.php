<?php
declare(strict_types=1);

use Slim\Http\Request;
use Slim\Http\Response;
use Chat\API\Exception\ErrorMsg;
use Chat\API\Exception\MissingRecipientException;
use Chat\API\Exception\InvalidRecipientException;
use Chat\API\Exception\AuthException;

$container = new \Slim\Container([
  'displayErrorDetails' => getenv("ENV") === "development", // set to false in production
  "debug" => getenv("ENV") === "development",
  'determineRouteBeforeAppMiddleware' => true,
  'addContentLengthHeader' => true // Allow the web server to send the content-length header
]);

$container["notFoundHandler"] = function($container) {
    return function(Request $request, Response $response) : Response {
        $error = new ErrorMsg(
            404,
            "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/404",
            "Not found",
            "The URI " . $request->getUri() . " is not implemented on this server",
            strval($request->getUri()),
            "NOT_FOUND"
        );
        return $response->withStatus(404)->withJson($error);
    };
};

$container["notAllowedHandler"] = function($container) {
    return function(Request $request, Response $response, array $allowedMethods) : Response {
        $error = new ErrorMsg(
            405,
            "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/405",
            "Method Not Allowed",
            "The method " . $request->getMethod() . " is not allowed methods are " . implode(", ", $allowedMethods),
            strval($request->getUri()),
            "NOT_ALLOWED"
        );
        return $response->withStatus(405)->withJson($error);
    };
};

$container["errorHandler"] = function($container) {
    return function(Request $request, Response $response, \Exception $exception) : Response {
        $status = 500;
        $type = "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/500";
        $title = "Internal Server Error";
        $details = "An error occurred ".$exception->getMessage();
        $instance = $request->getUri();
        $errorCode = "INTERNAL_SERVER_ERROR";
        if ($exception instanceof InvalidRecipientException) {
            $status = 400;
            $type = "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/400";
            $title = "Bad Request";
            $details = $exception->getMessage();
            $errorCode = "INVALID_RECIPIENT";
        } else if ($exception instanceof MissingRecipientException) {
            $status = 400;
            $type = "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/400";
            $title = "Bad Request";
            $details = $exception->getMessage();
            $errorCode = "MISSING_RECIPIENT";
        } else if ($exception instanceof AuthException) {
            $status = 403;
            $type = "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/403";
            $title = "Forbidden";
            $details = $exception->getMessage();
            $errorCode = "FORBIDDEN";
        }
        $error = new ErrorMsg(
            $status, $type, $title, $details, strval($instance), $errorCode
        );
        return $response->withStatus($status)->withJson($error);
    };
};

$container["phpErrorHandler"] = function($container) {
    return function(Request $request, Response $response, \Throwable $throwable) : Response {
        $error = new ErrorMsg(
            500,
            "https://developer.mozilla.org/en-US/docs/Web/HTTP/Status/500",
            "Internal Server Error",
            "An error occurred: " . $throwable->getMessage(),
            strval($request->getUri()),
            "INTERNAL_SERVER_ERROR"
        );
        return $response->withStatus(500)->withJson($error);
    };
};


return $container;
