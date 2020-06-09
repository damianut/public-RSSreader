<?php
declare(strict_types=1);
/**
 * This file is part of 'RSSreader' project.
 * 'RSSreader' retrieve newest articles from three particular pages
 * and display them.
 * 
 * (copyright) Damian Orzeszek <damianas1999@gmail.com>
 * 
 * 
 * CONFIGURATION
 * 
 * File for creating environment variables and requiring rest needed PHP files
 */

//Credentials for connect with database:
$_ENV['DB_HOST'] = '127.0.0.1';
$_ENV['DB_USER'] = 'rsser';
$_ENV['DB_PASS'] = 'RsS$oi)';
$_ENV['DB_NAME'] = 'rss_reader';

//Messages:
$_ENV['user.blocked'] = "You have been blocked for too often refreshing the page.";
$_ENV['arts_rmf24'] = "Artykuły z rmf24.pl:<br><br>";
$_ENV['arts_komputerswiat'] = "<br><br>Artykuły z komputerswiat.pl:<br><br>";
$_ENV['arts_xmoon'] = "<br><br>Artykuły z xmoon.pl:<br><br>";

//Errors:
$_ENV['user.blocked.fail'] = "Blocking fail.";
$_ENV['internal'] = "Internal error. Site temporarily blocked.";
$_ENV['query'] = "Location: https://duckduckgo.com?q=Do+not+append+any+query+to+URL&ia=software";
$_ENV['destination'] = 'Location: https://duckduckgo.com?q=You+are+blocked+for+too+much+refreshing+page&ia=software';
$_ENV['rss.503'] = "<br>Strona z RSS nie odpowiada.<br>";

require_once 'Articles/Articles.php';
require_once 'Browser/CookiesHandling.php';
require_once 'ConnectionDB/ConnectionDB.php';
require_once 'InfluenceDBTable/IpNumbers.php';
require_once 'Security/TextProcessing.php';
require_once 'MainClass.php';
/*............................................................................*/