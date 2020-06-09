<?php
declare(strict_types=1);
/**
 * This file is part of 'RSSreader' project.
 * 'RSSreader' retrieve newest articles from three particular pages
 * and display them.
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 */

namespace App\Browser;

/**
 * Class for influencing on cookies.
 */
class CookiesHandling
{
  /**
   * Set cookies
   * 
   * @param  string $ip       IP of user
   * @param  string $refreshs Number of refreshing
   * @param  string $moment   Date and time of last refreshing
   * @param  string $blocked  Indicate that user should be blocked
   * 
   * @return bool   $status   True, if all cookies was set, false otherwise.
   */
  public function setCookies(string $ip, string $refreshs, string $moment, string $blocked)
  {
    $expire = time() + 60*60*24*90;
    do {
      if (!setcookie('ip', $ip, $expire)) {
        break;
      }
      if (!setcookie('refreshs', $refreshs, $expire)) {
        break;
      }
      if (!setcookie('moment', $moment, $expire)) {
        break;
      }
      if (!setcookie('blocked', $blocked)) {
        break;
      }
      $status = true;
    } while (false);
    
    return $status ?? false;
  }
}
/*............................................................................*/