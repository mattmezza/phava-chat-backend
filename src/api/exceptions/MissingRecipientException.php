<?php
declare(sctict_types=1);

namespace Chat\API\Exception;

class MissingRecipientException extends \Exception
{
  /**
   * Builds a new instance of the exception
   */
  public function __construct()
  {
    parent::__construct("You didn't specify a `recipient` numeric field.");
  }
}
