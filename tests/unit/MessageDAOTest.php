<?php
declare(strict_types=1);

namespace Test\Chat\Unit;

use PHPUnit\Framework\TestCase;
use Chat\DB;
use Chat\MessageDAO;

class MessageDAOTest extends TestCase
{
  private $db;
  private $messageDAO;

  public function setup() : void
  {
    $this->db = new DB(getenv("CHAT_DB"));
    $this->db->exec("delete from messages");
    $this->messageDAO = new MessageDAO();
  }

  public function testAddMessage() : void
  {
    $text = "Provaprovaprova";
    $authorId = 1;
    $recipientId = 2;
    $id = $this->messageDAO->addMessage($text, $authorId, $recipientId);
    $stmt = $this->db->prepare("select * from messages where id = ?");
    $stmt->execute([$id]);
    $rows = $stmt->fetchAll();
    $this->assertEquals(1, count($rows));
    $this->assertEquals($text, $rows[0]["text"]);
    $this->assertEquals($authorId, $rows[0]["author"]);
    $this->assertEquals($recipientId, $rows[0]["recipient"]);
  }

  public function testGetMessageForUser() : void
  {
    $this->db->exec("insert into messages (text, recipient, author, created_at) values 
      ('First', 1, 2, '2018-03-19 00:00:01'),
      ('Segundo', 1, 2, '2018-03-19 00:00:02'),
      ('Terzo', 1, 2, '2018-03-19 00:00:03'),
      ('Dirtinci', 1, 2, '2018-03-19 00:00:04')
    ");
    $messages = $this->messageDAO->getMessagesForUser(1, 0, 20);
    $this->assertEquals(4, count($messages));
    $this->assertEquals("First", ($messages[0])->getText());
    $messages2 = $this->messageDAO->getMessagesForUser(1, 1, 1);
    $this->assertEquals(1, count($messages2));
    $this->assertEquals("Terzo", ($messages2[0])->getText());
  }
}
