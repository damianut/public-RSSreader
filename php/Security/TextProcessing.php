<?php
declare(strict_types=1);
/**
 * This file is part of 'RSSreader' project.
 * 'RSSreader' retrieve newest articles from three particular pages
 * and display them.
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Security;

/**
 * Text processing in purposes of security
 */
class TextProcessing
{
  /**
   * Remove or replace potentially dangerous chars
   * 
   * @param string $data String to process
   */   
  public function testData(string $data): string
  {
    $data = str_replace("<\/br>", "", $data);
    $data = str_replace(" <br\/>", "", $data);
    $data = str_replace("<br\/>", "", $data);
    $data = htmlspecialchars($data, ENT_QUOTES);
    $data = strip_tags($data);
    $data = stripslashes($data);
    $data = trim($data);
    $data = str_replace("\\", "", $data);
    $data = str_replace("quot;", "", $data);
    $data = str_replace("javascript", "js", $data);
    $data = str_replace(";", ",", $data);
    $data = str_replace("  ", " ", $data);
    
    return $data;
  }
}