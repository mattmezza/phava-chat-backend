<?php
declare(strict_types=1);

namespace Chat\API\Exception;

class AuthException extends \Exception
{
  /**
   * Builds a new instance of the exception
   */
  public function __construct()
  {
    parent::__construct("Each request should come with a header field `X-USERID` set to an integer representing the user id of the client.");
  }
}
