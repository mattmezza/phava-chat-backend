<?php
declare(strict_types=1);

namespace Chat;

use \PDO;
use \PDOException;

/**
 * A handy class to get a PDO db connection obj
 */
class DB
{

  /**
   * The PDO connection obj
   */
  protected $db;

  /**
   * Builds a new instance and connects it to the DB
   * 
   * @param   string    $which   The DB connection string
   * 
   */
  public function __construct(string $which)
  {
    $this->db = new PDO($which);
  }

  /**
   * Magic method that routes the calls to the actual $this->db object instance
   */
  public function __call($name, $arguments)
  {
    return call_user_func_array([$this->db, $name], $arguments);
  }

  /**
   * Creates the DB with all the tables
   */
  public function createDB() : void
  {
    $this->db->exec("
      CREATE TABLE IF NOT EXISTS `messages` (
        `id` integer PRIMARY KEY AUTOINCREMENT,
        `text` text NOT NULL,
        `author` integer NOT NULL,
        `recipient` integer NOT NULL,
        `created_at` text NOT NULL DEFAULT CURRENT_TIMESTAMP
      );
    ");
  }

}
