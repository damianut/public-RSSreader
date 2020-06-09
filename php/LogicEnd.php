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
 * LOGIC END
 * 
 * Prepare and send log about performed actions for server maintaining
 * purposes. Unset all variables from gl
 */

$scriptEnd = date("d-m-Y G:i:s");
$newLog =
    "The time when the script was started: ".
    $scriptBegun.
    "\n\n\n".
    $logAddArticles[0].
    $logAddArticles[1].
    $logAddArticles[2].
    "\nThe time when the execution of the script finished: ".
    $scriptEnd.
    "\n\n".
    '[=======================================================================]'.
    "\n\n"
;
$file = fopen("accesslog/accesslog.txt", "a");
fwrite($file, $newLog);
fclose($file);
/**
 * Unsetting rest of unnecessary variables to prevent retrieving sensitive data
 * from this variables.
 */
unset($scriptEnd, $newLog, $scriptBegun, $logAddArticles, $file, $intros,
$articlesDivContent);
/*............................................................................*/