<?php

declare(strict_types=1);

namespace App\Infrastructure\Query;

class QueryRepository
{
  private $db;

  public function __construct($db)
  {
    $this->db = $db;
  }

  public function executeQuery(string $query, array $parameters)
  {
    $stmt = $this->db->prepare($query);
    $stmt->execute($parameters);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }
}
