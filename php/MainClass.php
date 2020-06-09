<?php
declare(strict_types=1);
/**
 * This file is part of 'RSSreader' project.
 * 'RSSreader' retrieve newest articles from three particular pages
 * and display them.
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App;

use \App\Articles\Articles;
use \App\Browser\CookiesHandling;
use \App\ConnectionDB\ConnectionDB;
use \App\InfluenceDBTable\IpNumbers;
use \App\Security\TextProcessing;

class MainClass {
  /**
   * Performing actions concern articles
   * 
   * @var Articles
   */
  private $arts;
    
  /**
   * Influencing on cookies
   * 
   * @var CookiesHandling
   */
  private $cooker;
    
  /**
   * Performing actions of connection with database.
   * 
   * @var ConnectionDB
   */
  private $connecter;
  
  /**
   * Performing actions on $_ENV['DB_NAME'].`ip_numbers` table.
   * 
   * @var IpNumbers
   */
  private $ipNumbers;
  
  /**
   * Text processing for securit reasons.
   * 
   * @var TextProcessing
   */
  private $textSecurity;
  
  /**
   * Construct
   */
  public function __construct(
      Articles $arts,
      CookiesHandling $cooker,
      ConnectionDB $connecter,
      IpNumbers $ipNumbers,
      TextProcessing $textSecurity
  ){
    $this->arts = $arts;
    $this->cooker = $cooker;
    $this->connecter = $connecter;
    $this->ipNumbers = $ipNumbers;
    $this->textSecurity = $textSecurity;
  }
  
  /**
   * Function to check that user refreshing page too often.
   * 
   * @return string|bool $status False, if user doesn't refreshing too often, message otherwise
   */
  public function checkUser()
  {
    do {
      $now = new \DateTime();
      $moment = $now->format("Y-m-d G:i:s");
      if (!(true === $this->connecter->establishConnection())) {
        $status = $_ENV['internal'];
        break;
      }
      /**
       * Adres IP z kropkami zamienionymi na litery 'D' przyda się do
       * komunikacji z bazą danych.
       */
      $ip = str_replace('.', 'D', $_SERVER['REMOTE_ADDR']);
      
      /**
       * Te instrukcje warunkowe służą sprawdzeniu, czy użytkownik odświeża
       * ciągle stronę. Jeśli tak, to oznaczam w bazie danych jego IP jako
       * zablokowane.
       * 
       * Najpierw sprawdzam, czy użytkownik ma na swoim komputerze ciasteczka.
       * Jeśli tak, to oznacza to, że był już na tej stronie, czyli w bazie
       * danych powinny być o nim informacje, a także o jego IP.
       * 
       * Jeśli użytkownik ma ciasteczka na swoim komputerze, to prostu dodaję
       * 1 do licznika odświeżeń.
       */
      if (
        isset(
            $_COOKIE['ip'],
            $_COOKIE['refreshs'],
            $_COOKIE['moment'],
            $_COOKIE['blocked']
        )
      ) {
        /**
         * If cookie with number of refresh is an integer, increment them.
         * Else it means that malicious user influenced on this cookie or
         * another uncaught error occurred - by this error wasn't cause by me.
         * So I set 200 to cookie with number of refresh, to punish malicious
         * user.
         */
        $refreshs = intval($_COOKIE['refreshs']);
        $refreshs = !(0 === $refreshs) ? $refreshs + 1 : 200;
      } else {
        /**
         * Jeśli nie ma pobranych ciasteczek, to wtedy nie wiem, czy był już na
         * mojej stronie. Dlatego sprawdzam to w mojej bazie danych.
         * 
         * Jeśli pobrano rekordy, to był już na mojej stronie; a jeśli nie,
         * to go nie było lub wystąpił błąd.
         */
        $selectSql = "SELECT * FROM ip_numbers WHERE ip='$ip'";
        $queried = $this->connecter->connection->query($selectSql);
        if (!$queried) {
          $status = $_ENV['internal'];
          break;
        }
        $result = $queried->fetch_all(MYSQLI_ASSOC);
        /**
         * Najpierw ustalam, ile razy użytkownik odświeżył stronę.
         */
        if (0 === $queried->num_rows) {
          $refreshs = 1;
        } else if (1 === $queried->num_rows) {
          $result = $result[0];
          /**
           * Jeśli został pobrany jeden wiersz, to sprawdzam, czy nie oznaczyłem
           * tego użytkownika jako zablokowanego.
           * Jeśli tak, to skrypt nie jest dalej wykonywany.
           */
          if ('YES' === $result['blocked']) {
            $status = $_ENV['user.blocked'];
            break;
          }
          /**
           * Jeśli nie jest zablokowany, to sprawdzam; kiedy ostatnio
           * użytkownik był na mojej stronie.
           */
          $lastMoment = new \DateTime($result['moment']);
          $delta = $now->getTimestamp() - $lastMoment->getTimestamp();
          /**
           * Jeśli był na niej w ciągu 15 minut; to zapisuję w ciasteczku ilość
           * jego poprzednich odświeżeń stron. W przeciwnym razie resetuję
           * licznik odświeżeń.
           */
          $refreshs = $delta <= 900 ? $result['refresh'] + 1 : 1;
        } else if (1 < $queried->num_rows) {
          $refreshs = 1;
        }
      }
      $status = false === $this->checkRefreshing($refreshs, $ip, $moment)
          ? false : $_ENV['user.blocked'];
      $blocked = false === $status ? 'NO' : 'YES';
      if (false === $status) {
        $this->rowsNumberAction($refreshs, $moment, $ip, $blocked);
      }
      /**
       * Maybe in the future status of set cookies i.e. '$cooked'
       * will be needed.
       */
      $cooked = $this->cooker->setCookies(
          $ip,
          (string) $refreshs,
          $moment,
          $blocked
      );
    } while (false);
    $this->connecter->connection->close();
    
    return $status;
  }
  
  /**
   * Function for adding new arts to database
   * 
   * @return array $artsResponse Messages about added articles and logs messages
   */
  public function addNewArts(): array
  {
    /**
     * Add new articles to database and show newest.
     */
    $artResponse[0] = $this->arts->addNewArt(
        'arts_rmf24',
        'https://www.rmf24.pl/sport/feed',
        'item',
        'pubDate',
        'title',
        'description',
        '[<\/a>(.*)<\/p>]ms',
        'guid'
    );
    $artResponse[1] = $this->arts->addNewArt(
        'arts_komputerswiat',
        'https://www.komputerswiat.pl/.feed',
        'entry',
        'published',
        'title',
        'summary',
        '[align="right" />(.*)]ms',
        'id'
    );	
    $artResponse[2] = $this->arts->addNewArt(
        'arts_xmoon',
        'http://xmoon.pl/rss/rss.xml',
        'entry',
        'published',
        'title',
        'content',
        '[/>(.*)]ms',
        'id'
    );
    $artsResponse = [];
    $artsResponse[] = [
        $artResponse[0][0],
        $artResponse[1][0],
        $artResponse[2][0],
    ];
    $artsResponse[] = [
        $artResponse[0][1],
        $artResponse[1][1],
        $artResponse[2][1],
    ];
    $artsResponse[] = [
        $artResponse[0][2],
        $artResponse[1][2],
        $artResponse[2][2],
    ];
    
    return $artsResponse;
  }

  /**
   * Function for showing arts on page
   * 
   * @return array $articlesDivContent Array with articles
   */
  public function showArts(): array
  {
    $tablesNames = ["arts_rmf24", "arts_komputerswiat", "arts_xmoon"];
    $articlesDivContent = [];
    foreach ($tablesNames as $tableName) {
      $articlesDivContent[] = $this->arts->showArts(
          $tableName,
          $_ENV[$tableName]
      );   
    }
    $articlesDivContent[2] = str_replace("\n", ' ', $articlesDivContent[2]);

    return $articlesDivContent;
  }

  /**
   * Perform action on database depends of number of rows fetched in previous
   * query (this rows contains IP given in query)
   * 
   * @param  int         $refreshs Number of refreshing
   * @param  string      $moment   Date and time of last refreshing
   * @param  string      $ip       IP of user
   * @param  string      $blocked  Indicate that user should be blocked 
   *  
   * @return string|bool $status   "$_ENV['internal']" if internal error occurred,
   *                               "$_ENV['redirect']" if user should be redirect and action performing was succeeded
   *                               "true" if another actions was succeded
   */
  private function rowsNumberAction(int $refreshs, string $moment, string $ip, string $blocked = 'NO')
  {
    do {
      $sql = "SELECT * FROM ip_numbers WHERE ip='$ip'";
      $queried = $this->connecter->connection->query($sql);
      if (!$queried) {
        $status = $_ENV['internal'];
        break;
      }
      $this->ipNumbers->setReq(
          $this->connecter->connection,
          $ip,
          $refreshs,
          $moment,
          $blocked
      );
      if (0 === $queried->num_rows) {
        $status = $this->ipNumbers->insertUser();
      } else if (1 === $queried->num_rows) {
        $status = $this->ipNumbers->updateUser();
      } else if (1 < $queried->num_rows) {
        $status = $this->ipNumbers->deleteUser();
        if (true === $status) {
          $status = $this->ipNumbers->insertUser();
        }
      } 
    } while (false);

    return $status;
  }

  /**
   * Function to blocking user depends on number of page refreshing and IP.
   * 
   * @param  int         $f5ing   Number of refreshing
   * @param  string      $ip      IP of user
   * @param  string      $moment  Time of last refreshing
   * 
   * @return string|bool          "$_ENV['internal']" if internal error occurred,
   *                              "true" if user was blocked
   *                              "false" if user wasn't block and shouldn't be block
   */
  private function checkRefreshing(int $f5ing, string $ip, string $moment)
  {
    return $f5ing >= 200 ?
        $this->rowsNumberAction($f5ing, $moment, $ip, 'YES') : false;
  }
}
/*............................................................................*/