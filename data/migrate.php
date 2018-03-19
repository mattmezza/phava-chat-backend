<?php
declare(strict_types=1);
echo getenv("CHAT_DB");
require 'vendor/autoload.php';

try {
  $db = new Chat\DB(getenv("CHAT_DB"));
  $db->createDB();
  die("DB created!");
} catch(\PDOException $e) {
  die("Error creating DB: " . $e->getMessage());
}
