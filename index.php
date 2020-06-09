<?php
require_once 'php/Configuration.php';
require_once 'php/ClassLoading.php';
require_once 'php/LogicStart.php';
?>
<!DOCTYPE html>
<html lang='pl-PL'>
	<head>
		<meta charset='UTF-8'>
		<title>RSS Reader</title>
		<link rel='stylesheet' href='css/index.css'>
	</head>
	<body>
        <div class='info' id='intro1'><?php print($intros[0]); ?></div>
		<div class='info' id='articles1'><?php print($articlesDivContent[0]); ?></div>
        <div class='info' id='intro2'><?php print($intros[1]); ?></div>
		<div class='info' id='articles2'><?php print($articlesDivContent[1]); ?></div>
        <div class='info' id='intro3'><?php print($intros[2]); ?></div>
		<div class='info' id='articles3'><?php print($articlesDivContent[2]); ?></div>
	</body>
</html>
<?php
require_once 'php/LogicEnd.php';