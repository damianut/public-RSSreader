<?php
declare(strict_types=1);
/**
 * This file is part of 'RSSreader' project.
 * 'RSSreader' retrieve newest articles from three particular pages
 * and display them.
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\InfluenceDBTable;

/**
 * Class for performing actions on $_ENV['DB_NAME'].`ip_numbers` table.
 */
class IpNumbers
{
  /**
   * Connection with database.
   * 
   * @var \mysqli
   */
  private $connection;
  
  /**
   * IP of user
   * 
   * @var string
   */
  private $ip;
  
  /**
   * Number of refreshs of user
   * 
   * @var int
   */
  private $refreshs;
  
  /**
   * Date and time of last refresh
   * 
   * @var string
   */
  private $moment;
  
  /**
   * String to indicate that user should be blocked or not
   * 
   * @var string
   */
  private $blocked;
  
  /**
   * Set required data i.e. user's data and reference to connection with db
   * 
   * @param \mysqli $connection Current connection
   * @param int     $refreshs   Number of refreshing
   * @param string  $moment     Date and time of last refreshing
   * @param string  $ip         IP of user
   * @param string  $blocked    Indicate that user should be blocked 
   */
  public function setReq(\mysqli $connection, string $ip, int $refreshs, string $moment, string $blocked = 'NO')
  {
    $this->connection = $connection;
    $this->ip = $ip;
    $this->refreshs = $refreshs;
    $this->moment = $moment;
    $this->blocked = $blocked;
  }
  
  /**
   * Check that user's data and connection is set.
   * 
   * @return bool True if all variables are set, false otherwise
   */
  private function isSet(): bool
  {
    return isset(
        $this->connection,
        $this->ip,
        $this->refreshs,
        $this->moment,
        $this->blocked
    );
  }
  
  /**
   * Insert new data to row about user.
   * 
   * @return string|bool             Message about performed actions
   */
  public function insertUser()
  {
    $sql =
        "INSERT INTO ip_numbers (ip, refresh, moment, blocked) VALUES".
        " ('".
        $this->ip.
        "', '".
        $this->refreshs.
        "', '".
        $this->moment.
        "', '".
        $this->blocked.
        "');"
    ;
        
    return $this->influenceUser($sql);
  }
  
  /**
   * Update row about user.
   * 
   * @return string|bool             Message about performed actions
   */
  public function updateUser()
  {
    $sql =
        "UPDATE ip_numbers SET refresh = '".
        $this->refreshs.
        "', moment = '".
        $this->moment.
        "', blocked = '".
        $this->blocked.
        "' WHERE ip = '".
        $this->ip.
        "';"
    ;
    
    return $this->influenceUser($sql);  
  }
  
  /**
   * Delete row about user.
   * 
   * @return string|bool             Message about performed actions
   */
  public function deleteUser()
  {
    $sql = "DELETE * FROM ip_numbers WHERE ip='$ip'";
    
    return $this->influenceUser($sql);
  }
  
  /**
   * Generally defined performing action on $_ENV['DB_NAME'].`ip_numbers`
   * 
   * @param \mysqli $connection Current connection with database
   * @param string  $sql        SQL to query to database
   * 
   * @return string|bool             Message about performed actions
   */
  private function influenceUser(string $sql)
  {
    if (!$this->isSet()) {
      $msg = "Required data aren't set by ".IpNumbers::class.".";
      throw new \Exception($msg);   
    }
      
    return $this->connection->query($sql) ? true : $_ENV['internal'];   
  }
}
/*............................................................................*/