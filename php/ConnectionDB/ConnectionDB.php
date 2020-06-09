<?php
declare(strict_types=1);
/**
 * This file is part of 'RSSreader' project.
 * 'RSSreader' retrieve newest articles from three particular pages
 * and display them.
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\ConnectionDB;

/**
 * Class for establishing, refreshing, closing and checking connection
 * with database described in 'php/configuration.php'
 */
class ConnectionDB
{
  /**
   * Connection with database.
   * 
   * @var \mysqli
   */
  public $connection;
  
  /**
   * Establishing connection with database.
   * 
   * @return string|bool True, if establishing succeeded, message otherwise
   */
  public function establishConnection()
  {
    $this->connection = new \mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME']
    );
    if ($this->connection->connect_error) {
    	$message = "Connection failed: " . $this->connection->connect_error;
    }
    
    return $message ?? true;
  }
  
  /**
   * Function to resuming connection with database
   * 
   * @return string|bool True, if refreshing succeeded, message otherwise
   */   
  public function refreshConnection()
  {
    $this->connection->close();
    
    return $this->establishConnection();
  }
}
/*............................................................................*/