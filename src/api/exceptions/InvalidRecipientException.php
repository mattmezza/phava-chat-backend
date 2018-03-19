<?php
declare(strict_types=1);

namespace Chat\API\Exception;

class InvalidRecipientException extends \Exception
{
  /**
   * The invalid value for the recipient
   */
  private $recipient;
  /**
   * Builds a new instance of the exception
   */
  public function __construct($recipient)
  {
    parent::__construct("The recipient you specified is invalid, please specify a `recipient` integer field.");
    $this->recipient = $recipient;
  }
  
}
