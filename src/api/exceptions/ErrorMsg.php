<?php
declare(strict_types=1);

namespace Chat\API\Exception;

/**
 * A class implementing the RFC 7808 for error message
 */
class ErrorMsg implements \JsonSerializable
{
  protected $status;
  protected $type;
  protected $title;
  protected $detail;
  protected $instance;
  protected $errorCode;

  /**
   * Builds an instance of ErrorMsg
   */
  public function __construct(int $status, string $type, string $title, string $detail, string $instance, string $errorCode)
  {
    $this->status = $status;
    $this->type = $type;
    $this->title = $title;
    $this->detail = $detail;
    $this->instance = $instance;
    $this->errorCode = $errorCode;
  }

  /**
   * Gives a representation used to serialize the object to json with json_encode
   * 
   * @return    array   The array to serialize
   */
  public function jsonSerialize() : array
  {
    return [
      "status" => $this->status,
      "type" => $this->type,
      "title" => $this->title,
      "detail" => $this->detail,
      "instance" => $this->instance,
      "error_code" => $this->errorCode
    ];
  }

}
