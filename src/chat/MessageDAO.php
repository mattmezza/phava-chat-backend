<?php
declare(strict_types=1);

namespace Chat;

class MessageDAO
{
  /**
   * The DB instance
   */
  private $db;

  /**
   * Builds a new instance of MessageDAO
   */
  public function __construct()
  {
    $this->db = new DB(getenv("CHAT_DB"));
  }

  /**
   * Gets the paginated messages for a user
   * 
   * @param   int   $userId   The id of the recipient
   * @param   int   $page     The page to retrieve
   * @param   int   $perpage  How many messages to retrieve per page.
   * 
   * @return  array   The array of the retrieved messages.
   */
  public function getMessagesForUser(int $userId, int $page, int $perpage) : array
  {
    $limitFrom = $page * $perpage;
    $stmt = $this->db->prepare("SELECT * FROM messages WHERE recipient = ? ORDER BY created_at DESC LIMIT $limitFrom,$perpage");
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();
    $messages = [];
    foreach ($rows as $row) {
      $messages[] = new Message(
        intval($row["id"]),
        trim($row["text"]),
        intval($row["author"]),
        intval($row["recipient"]),
        \DateTime::createFromFormat("Y-m-d H:i:s", $row["created_at"])
      );
    }
    return array_reverse($messages);
  }

  /**
   * Inserts a message row
   * 
   * @param   string    $text   The text of the message to add
   * @param   int   $authorId   The id of the author
   * @param   int   $recipientId    The id of the recipient
   * 
   * @return    int   The id of the newly added message row.
   */
  public function addMessage(string $text, int $authorId, int $recipientId) : int
  {
    $stmt = $this->db->prepare("INSERT INTO messages (text, author, recipient) VALUES (?,?,?)");
    $stmt->execute([$text, $authorId, $recipientId]);
    return intval($this->db->lastInsertId());
  }
}
