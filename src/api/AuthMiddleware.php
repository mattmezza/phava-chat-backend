<?php
declare(strict_types=1);

namespace Chat\API;

use \Psr\Http\Message\ResponseInterface;
use \Psr\Http\Message\ServerRequestInterface;
use Chat\User;
use Chat\API\Exception\AuthException;

class AuthMiddleware
{
  /**
   * A middleware invokable class to authenticate the user
   *
   * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
   * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
   * @param  callable                                 $next     Next middleware
   *
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next) : ResponseInterface
  {
    if (!$request->hasHeader('X-USERID')) {
      throw new AuthException();
    }
    $userId = trim($request->getHeader('X-USERID')[0]);
    if (!is_numeric($userId)) {
      throw new AuthException();
    }
    // creating the user
    $user = new User(intval($userId));
    // injecting the user into the request
    $request = $request->withAttribute("user", $user);
    $response = $next($request, $response);

    return $response;
  }
}
