<?php
declare(strict_types=1);
/**
 * This file is part of 'RSSreader' project.
 * 'RSSreader' retrieve newest articles from three particular pages
 * and display them.
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Articles;

use \App\ConnectionDB\ConnectionDB;
use \App\Security\TextProcessing;

/**
 * Class for performing actions that concern articles
 */
class Articles
{
  /**
   * Performing actions of connection with database.
   * 
   * @var ConnectionDB
   */
  private $connecter;
  
  /**
   * Text processing for securit reasons.
   * 
   * @var TextProcessing
   */
  private $textSecurity;
  
  /**
   * Constructor
   */
  public function __construct(
      ConnectionDB $connecter,
      TextProcessing $textSecurity
  )
  {
    $this->connecter = $connecter;
    $this->textSecurity = $textSecurity;
  }

  /**
   * Function to showing, which articles are in database.
   * @param  string       $whichTable Name of table from which articles should be showed
   * @param  string       $intro      Beginning of arts
   * 
   * @return string|bool  $arts       Arts if retrieving succedded, $_ENV['internal'] message otherwise.
   */
  public function showArts(string $whichTable, string $intro): string
  {
    $arts = $intro;
    $sql =
        "SELECT id, unique_id, published, title, content, reg_date".
        " FROM ".
        $whichTable
    ;
    do {
      if (!(true === $this->connecter->establishConnection())) {
        $arts = $_ENV['internal'];
        break;
      }
      $result = $this->connecter->connection->query($sql);
      if (false === $result) {
        $arts = $_ENV['internal'];
        break;  
      }
      if ($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        for ($i = 0; $i < $result->num_rows; $i++) {
          $row[intval($rows[$i]["id"])] = $rows[$i];
        }
        for ($i = 1; $i <= $result->num_rows; $i++) {
          $arts .=
              $row[$i]["id"].
              ". Unikalny identyfikator: ".
              $row[$i]["unique_id"].
              "<br>Data i godzina publikacji: ".
              $row[$i]["published"].
              "<br><br>Tytuł: ".
              $row[$i]["title"].
              "<br><br>Treść artykułu: ".
              $row[$i]["content"].
              "<br><br>Data i godzina dodania do bazy: ".
              $row[$i]["reg_date"].
              "<br><br><br>"
          ;
        }
      } else {
        $arts .= "\nBrak artykułów na stronie.";
      }
    } while (false);

    return $arts;
  }
  
  /**
   * Function for actualisation of articles in database
   * 
   * @param  string $whichTable                      Name of table with articles for given site
   * @param  string $uri                             URL of site
   * @param  string $itemFormat                      Name of element's tag with article and metadata about article
   * @param  string $dateFormat                      Name of element's tag with publishing's date of article
   * @param  string $titleFormat                     Name of element's tag with article's title
   * @param  string $contentFormat                   Name of element's tag with article's content
   * @param  string $contentFormatSpecified          Code to remove from content to receive more pure text
   * @param  string $uniqueIdFormat                  Name of element's tag with unique id
   * 
   * @return array  [$clientMessage, $logAddArticle, $status] Array with intro for articles and logs messages
   */
  public function addNewArt(string $whichTable, string $uri, string $itemFormat, string $dateFormat, string $titleFormat, string $contentFormat, string $contentFormatSpecified, string $uniqueIdFormat)
  {
    do {
      $logAddArticle = $clientMessage = $status =$newUpdate =
          $newDelete = $newRecord = '';
      if (!(true === $this->connecter->establishConnection())) {
        $status = $_ENV['internal'];
        break;
      }
      /**
       * 1) Downloading articles:
       */
      $fileXML = new \DOMDocument;
      $fileXML->load($uri);
      if ($fileXML->textContent === NULL){
        $status = $_ENV['rss.503'];
        break;
      }
    
      /**
       * 2) Fetching code from each article.
       */  
      $results = $fileXML->getElementsByTagName($itemFormat);
      $clientMessage .=
          "Liczba artykułów na stronie ".
          $uri.
          ": ".
          $results->length.
          "<br>"
      ;
 
      /**
       * 3) Next downloading from this code necessary informations:
       */   
      $i = -1;
  
      foreach ($results as $result) {
        $articles[++$i][0]  = $result
            ->getElementsByTagName($dateFormat)
            ->item(0)
            ->nodeValue
        ;
        $datetime = new \DateTime($articles[$i][0]);
        $articles[$i][0] = $datetime->format("Y-m-d G:i:s");
        $articles[$i][1] = $result
            ->getElementsByTagName($titleFormat)
            ->item(0)
            ->nodeValue
        ;
        $articles[$i][2] = $result
            ->getElementsByTagName($contentFormat)
            ->item(0)
            ->nodeValue;
    
        if ($contentFormatSpecified != 'nothing') {
          preg_match($contentFormatSpecified, $articles[$i][2], $articles[$i][2]);
          $articles[$i][2] = $articles[$i][2][1];
          $articles[$i][2] = $this->textSecurity->testData($articles[$i][2]);
        } else if($contentFormatSpecified === 'nothing'){
          $articles[$i][2] = $this->textSecurity->testData($articles[$i][2]);
        }
    
        $articles[$i][3] = $result
            ->getElementsByTagName($uniqueIdFormat)
            ->item(0)
            ->nodeValue
        ;
    
        $articles[$i][0] = $this->textSecurity->testData($articles[$i][0]);
        $articles[$i][1] = $this->textSecurity->testData($articles[$i][1]);
        $articles[$i][3] = $this->textSecurity->testData($articles[$i][3]);
      }
      /**
       * 4) Adding article(s) to MySQL database.
       *
       * 4.1) First retrieve all articles from table with given name:
       */
      $newSelect =
         "SELECT id, unique_id, published, title, content, reg_date FROM ".
         $whichTable;
      /**
       * In '$result' all articles from table are saved and in '$articles'
       * current articles from external site are saved.
       */
      $result = $this->connecter->connection->query($newSelect);
      if (!$result) {
        $status = $_ENV['internal'];
        break;
      }
                                                    
      /**
       * 4.2) Checking, how much articles are in table from database:
       */   
      $numArtsIs = $result->num_rows;
      
      /**
       * 4.2.1) If in table is another number of articles, than 5:
       */   
      if ($numArtsIs != 5) { 
        /**
         * 4.2.1.1) First removing all articles from table:
         */
        $newDelete = "TRUNCATE TABLE `".$whichTable."`;";
        if ($numArtsIs != 0) {
           $this->doQuery($newDelete, "deleting");
        }
        $this->connecter->refreshConnection();
 
        /**
         * 4.2.1.2) Now adding articles, which are from external site.
         * After removing articles from table, table is empty, and
         * AUTO_INCREMENT is set to '1':
         */
        if (sizeof($articles) > 4) {
          $artsOnPage = 5;
        } else if (sizeof($articles) === 0) {
          $clientMessage .= 
              "<br>Brak artykułów na stronie ".
              $uri.
              "<br>"
          ;
          $logAddArticle .= 
              "\nTo the table ".
              $whichTable.
              " no new articles have been added\n\n"
          ;
        } else {
          $artsOnPage = sizeof($articles);
        }
        $uniqueids[0] = 1;
        for ($k = 0; $k < $artsOnPage; $k++) {
          $uniqueids[$uniqueids[0]] = $articles[$k][3];
          $logAddArticle .= $articles[$k][3] . "\n\n";
          $uniqueids[0]++;
          $newRecord .=
              "INSERT INTO ".
              $whichTable.
              " (id, unique_id, published, title, content) VALUES (".
              ($k + 1).
              ", '".
              $articles[$k][3].
              "', '".
              $articles[$k][0].
              "', '".
              $articles[$k][1].
              "', '".
              $articles[$k][2].
              "'); "
          ;	
        }
        $logAddArticle = $this->doQuery(
            $newRecord,
            "creating records",
            $whichTable,
            $uniqueids
        );
        if ($logAddArticle === NULL) {
          $logAddArticle .=
              "\nTo the table ".
              $whichTable.
              " no new articles have been added\n\n"
          ;
        }
      } else if ($numArtsIs === 5) {
        /**
         * 4.3) If in table are 5 articles:
         * 
         * 4.3.1) Checking on which position in articles from '$articles'
         * (from external site) is first row from '$result' (from MySQL).
         * 
         * Creating array with current saved articles in table in database
         * in purpose of find first articles in table from database
         * (with id == 1).
         */
        for ($k = 0; $k < $numArtsIs; $k++) {
          $rows[$k] = $result->fetch_assoc();
          if ((int) $rows[$k]["id"] === 1) { 
            $firstRow = $rows[$k]; 
            $k = $numArtsIs;
          }
        }
        for ($k = 0; $k < sizeof($articles); $k++) {
          if ($articles[$k][3] === $firstRow["unique_id"]) {
            /**
             * Below number determines, which article in order from
             * external site, is article from table from database.
             */
            $numArtsAdd = $k + 1;  
            $k = sizeof($articles);
          }
        }
        $clientMessage = 
            "Pozycja najnowszego artykułu z bazy, wśród artykułów z ".
            $uri.
            ": ".
            $numArtsAdd ?? ''.
            "<br>Jeśli nie pojawił się numer pozycji, to najnowszy artykuł z bazy,".
            " nie jest żadnym z artykułów obecnie dostępnych na stronie ".
            "zewnętrznej.<br>"
        ;
        /**
         * If position of article won't determine, assign '6' value to below
         * variable in purpose of deleting all articles from table from database.
         */
        if ($numArtsAdd === NULL) {
          $numArtsAdd = 6;
        }
        /**
         * 4.3.2) If articles in table from database are newest:
         */
        if ($numArtsAdd === 1) { 
          $clientMessage .=  "<br>Brak nowych artykułów do dodania.<br>";
          $logAddArticle .=
              "\nTo the table ".
              $whichTable.
              " no new articles have been added\n\n"
          ;
          break;
        } else if(sizeof($articles) === 0) {
          $clientMessage .= "Brak artykułów na stronie ". $uri;
          $logAddArticle .=
              "\nTo the table ".
              $whichTable.
              " no new articles have been added\n\n"
          ;
          break;
        /**
         * 4.3.3) Case when it's necessary to remove from 1 to 5 articles:
         */
        } else if ($numArtsAdd > 1) {
          if ($numArtsAdd > 6) {
            $numArtsAdd = 6;
          }
          $newDelete = '';
          for ($k = 0; $k < ($numArtsAdd - 1); $k++) {
            $newDelete .= "DELETE FROM " . $whichTable . " WHERE id=" . (5 - $k);
            if($k === 0){
                if($numArtsAdd > 2){
                    $newDelete .= ";";
                }
            }
            else if($k > 0){
                if($k != ($numArtsAdd - 2)){
                    $newDelete .= ";";
                }
            }
          }
          $this->doQuery($newDelete, "deleting");
        }
        $this->connecter->refreshConnection();
/*............................................................................*/   
        /**
         * 4.3.4) Actualisation of articles's ID in database, if they are there:
         */
        if ($numArtsAdd < 6) {
          for ($k = (6 - $numArtsAdd); $k > 0; $k--) {
            $newUpdate .=
                "UPDATE ".
                $whichTable.
                " SET id='".
                ($k + $numArtsAdd - 1).
                "' WHERE id=".
                $k
            ;
            if ($k === (6 - $numArtsAdd)) {
              if ($numArtsAdd != 5) {
                  $newUpdate .= ";";
              }
            } else if ($k < (6 - $numArtsAdd)) {
              if ($k != 1) {
                  $newUpdate .= ";";
              }
            } 
          }
          $this->doQuery($newUpdate, "updating");
        }
        $this->connecter->refreshConnection();
    
        /**
         * 5.3.5) Adding articles in place of deleted:
         */
         $uniqueids[0] = 1;
        for ($k = 0; $k < ($numArtsAdd - 1); $k++) {
          $uniqueids[$uniqueids[0]] = $articles[$k][3];
          $uniqueids[0]++;
          $newRecord .=
              "INSERT INTO ".
              $whichTable.
              " (id, unique_id, published, title, content) VALUES ('".
              ($k + 1).
              "', '". 
              $articles[$k][3].
              "', '".
              $articles[$k][0].
              "', '".
              $articles[$k][1].
              "', '".
              $articles[$k][2].
              "')"
          ;
          if ($k === 0) {
            if ($numArtsAdd > 2) {
              $newRecord .= ";";			
            }
          } else if ($k > 0) {
            if ($k != ($numArtsAdd - 2)) {
              $newRecord .= ";";
            }
          }
        }
        $logAddArticle = $this->doQuery(
            $newRecord,
            "adding",
            $whichTable,
            $uniqueids 
        );
      }
      if ($logAddArticle === NULL) {
        $logAddArticle =
            "\nTo the table ".
            $whichTable.
            " no new articles have been added\n\n"
        ;
      }
    } while (false);
    $this->connecter->connection->close();
    
    return [$clientMessage, $logAddArticle, $status];
  }
 
  /**
   * Function for sending query.
   *
   * @param  string      $myQuery    SQL statement(s) to execute
   * @param  string      $message    Message about type of performing action(s)
   * @param  string      $whichTable Name of table with articles, on which action should be performed
   * @param  int         $uniqueids  Unique IDs of articles
   * 
   * @return mixed       $status     Message if sending succedded, false otherwise
   */  
  private function doQuery(string $myQuery, string $message = '', string $whichTable = '', array $uniqueids = [])
  {
    $this->connecter->refreshConnection();
    $result = $this->connecter->connection->multi_query($myQuery);
    if ("adding" === $message || "creating records" === $message) {
      if (true === $result) {
        $sumIds = "\n";
        for ($i = 1; $i < count($uniqueids); $i++) {
          $sumIds .= $uniqueids[$i]."\n";
        }
        $sumIds .= "\n\n";
        
        /**
         * It's possible to retrieve timestamp from server, but
         * it costs time and memory:
         */
        $status =
            "Now, that is: ".
            date("d-m-Y G:i:s").
            " article(s) was added \nTo the table ".
            $whichTable.
            " with unique id's:\n".
            trim($sumIds)
        ;
      } else {
        $status = false;
      }
    } else {
      $status = $result;
    }
    /**
     * Below while loop preserves that next actions on database
     * will be done after saving all new rows.
     */
    while ($this->connecter->connection->more_results()) {
      $this->connecter->connection->next_result();   
    }
    
    return $status ?? false;
  }
}
/*............................................................................*/