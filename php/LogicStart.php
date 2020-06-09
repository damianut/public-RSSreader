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
 * LOGIC START
 * 
 * File to prepare answers for client (User) and project's admin.
 * Flooding detecting and prevent malicious user's attack by URL.
 */

$scriptBegun = date('d-m-Y G:i:s');

/**
 * Below code serves to prevent attack on the server by typing code
 * in URL's query.
 */
if (!empty($_SERVER['QUERY_STRING']) || !empty($_SERVER['PATH_INFO'])) {
  header($_ENV['query']);
  exit;
}

header('Cache-Control: no-cache, must-revalidate');

/**
 * Checking, that malicious user isn't charging the server by continuous
 * page refreshing (flooding).
 */
$status = $mainClass->checkUser();
if ($_ENV['internal'] === $status) {
  echo $status;
  exit;
} else if (!(false === $status)) {
  header($_ENV['destination']);
  exit; 
} else {
/**
 * I'm leaving this condition's block empty to catch unexpected '$status'
 * while testing code in future.
 */    
}

/**
 * Loading articles and showing newest.
 */
$artsResponse = $mainClass->addNewArts();

/**
 * Third element of above array contains message if error occurred,
 * empty string otherwise.
 */
if (
    !('' === $artsResponse[2][0]) ||
    !('' === $artsResponse[2][1]) ||
    !('' === $artsResponse[2][2])
) {
  echo $_ENV['internal'];
  exit;   
}

/**
 * "$intros" contains introduction for articles from each site, from which
 * articles was loaded.
 * 
 * "$articlesDivContent" contains bodies of articles from each previously
 * described site.
 * 
 * "$logAddArticles" contains informations needed to creating log file.
 */
$intros = $artsResponse[0];
$logAddArticles = $artsResponse[1];
$articlesDivContent = $mainClass->showArts();
/**
 * Unsetting unnecessary variables to prevent retrieving sensitive data from
 * this variables.
 */
unset($connectionDBTemp, $textProcessingTemp, $articlesTemp, $_ENV,
$cookiesHandlingTemp, $ipNumbersTemp, $mainClass, $status, $artsResponse);
/*............................................................................*/