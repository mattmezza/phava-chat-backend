<?php
declare(strict_types=1);

namespace Chat\API;

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Container;
use Illuminate\Support;

use Chat\API\Exception\InvalidRecipientException;
use Chat\API\Exception\MissingRecipientException;

class MessageController
{
  protected $container;

  public function __construct(Container $container)
  {
    $this->container = $container;
  }

  public function getMessages(Request $request, Response $response) {
    // getting the user from the middleware
    $user = $request->getAttribute("user");
    // getting the page and perpage values
    $page = intval($request->getQueryParam("page", 0));
    $perpage = intval($request->getQueryParam("perpage", 20));
    // finally getting the messages
    $messages = $user->getMyMessages($page, $perpage);
    // getting the required projection of the data to return
    $messageRepresentation = collect($messages)->map(function($message) {
      return [
        "id" => $message->getId(),
        "sender" => $message->getAuthor(),
        "body" => $message->getText(),
        "sentAt" => $message->getCreatedAt()
      ];
    })->toArray();
    return $response->withStatus(200)->withJson($messageRepresentation);
  }

  public function sendMessage(Request $request, Response $response) {
    // getting the user from the middleware
    $user = $request->getAttribute("user");
    // getting the text to be sent
    $text = trim($request->getParsedBodyParam("text", ""));
    // getting the recipient of the message
    $recipient = $request->getParsedBodyParam("recipient", false);
    if (!$recipient) {
      throw new MissingRecipientException();
    }
    if (!is_numeric($recipient)) {
      throw new InvalidRecipientException($recipient);
    }
    // sending the message
    $messageId = $user->sendMessageTo(intval($recipient), $text);
    $sentAt = new \DateTime();
    
    // RFC 7808
    return $response->withStatus(201)->withJson([
      "status" => 201,
      "title" => "Message sent",
      "detail" => "Message sent with ID $messageId at " . $sentAt->format("d/m/Y H:i:s")
    ]);
  }

}
