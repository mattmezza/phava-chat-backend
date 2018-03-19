<?php
declare(strict_types=1);

namespace Chat;


class Message
{
  /**
   * The id of the message
   */
  private $id;
  
  /**
   * The message itself
   */
  private $text;

  /**
   * The author of the message
   */
  private $author;

  /**
   * The recipient of the message
   */
  private $recipient;

  /**
   * The creation date DateTime
   */
  private $createdAt;

  /**
   * Builds a Message instance
   */
  public function __construct(int $id, string $text, int $author, int $recipient, \DateTime $createdAt)
  {
    $this->id = $id;
    $this->text = $text;
    $this->author = $author;
    $this->recipient = $recipient;
    $this->createdAt = $createdAt;
  }

  /**
   * Get the message itself
   */ 
  public function getText() : string
  {
    return $this->text;
  }

  /**
   * Set the message itself
   *
   * @return  self
   */ 
  public function setText($text) : self
  {
    $this->text = $text;

    return $this;
  }

  /**
   * Get the author of the message
   */ 
  public function getAuthor() : int
  {
    return $this->author;
  }

  /**
   * Set the author of the message
   *
   * @return  self
   */ 
  public function setAuthor($author) : self
  {
    $this->author = $author;

    return $this;
  }

  /**
   * Get the recipient of the message
   */ 
  public function getRecipient() : int
  {
    return $this->recipient;
  }

  /**
   * Set the recipient of the message
   *
   * @return  self
   */ 
  public function setRecipient($recipient) : self
  {
    $this->recipient = $recipient;

    return $this;
  }

  /**
   * Get the creation date DateTime
   */ 
  public function getCreatedAt() : \DateTime
  {
    return $this->createdAt;
  }

  /**
   * Set the creation date DateTime
   *
   * @return  self
   */ 
  public function setCreatedAt($createdAt) : self
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * Get the id of the message
   */ 
  public function getId() : int
  {
    return $this->id;
  }

  /**
   * Set the id of the message
   *
   * @return  self
   */ 
  public function setId($id) : self
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Returns a string representation of the object
   */
  public function __toString() : string
  {
    return sprintf("[id %i - %s] from %i to %i: %s", [
      $this->id,
      $this->author,
      $this->recipient,
      $this->text,
      $this->createdAt->format("d/m/Y H:i:s")
    ]);
  }
}
