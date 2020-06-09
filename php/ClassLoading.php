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
use \App\MainClass;
use \App\InfluenceDBTable\IpNumbers;
use \App\Security\TextProcessing;

/**
 * Instantiation of used classes.
 */
$connectionDBTemp = new ConnectionDB;
$textProcessingTemp = new TextProcessing;
$articlesTemp = new Articles ($connectionDBTemp, $textProcessingTemp);
$cookiesHandlingTemp = new CookiesHandling;
$ipNumbersTemp = new IpNumbers;

$mainClass = new MainClass(
    $articlesTemp,
    $cookiesHandlingTemp,
    $connectionDBTemp,
    $ipNumbersTemp,
    $textProcessingTemp
);
/*............................................................................*/