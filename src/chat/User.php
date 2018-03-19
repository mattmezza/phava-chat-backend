<?php
declare(strict_types=1);

namespace Chat;

class User
{
  /**
   * The id of the user
   */
  private $id;

  /**
   * The Data Access Object to deal with
   * the DB representation of the Message
   */
  private $messageDAO;

  /**
   * Builds an User instance
   */
  public function __construct(int $id)
  {
    $this->id = $id;
    $this->messageDAO = new MessageDAO();
  }

  /**
   * Get the id of the user
   */ 
  public function getId() : int
  {
    return $this->id;
  }

  /**
   * Sends a message to a user
   * 
   * @param   int  $recipient  The user id to send the message to
   * @param   string   $text    The text message to send
   * 
   * @return    int   The id of the newly added message
   */
  public function sendMessageTo(int $recipient, string $text) : int
  {
    return $this->messageDAO->addMessage($text, $this->getId(), $recipient);
  }

  /**
   * Gets the messages (paginated)
   * 
   * @param   int   $page   The page requested
   * @param   int   $perpage    How many messages in each page
   * 
   * @return    array   The array containing all the messages
   */
  public function getMyMessages(int $page, int $perpage) : array
  {
    return $this->messageDAO->getMessagesForUser($this->getId(), $page, $perpage);
  }


  /**
   * Returns a string representation of a User
   */
  public function __toString() : string
  {
    return "User id [" . strval($this->id) . "]";
  }

}
