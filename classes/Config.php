<?php
$prdPossibleRootDir = array();
array_push($prdPossibleRootDir,"D:/test/www/pictures/");
array_push($prdPossibleRootDir,"D:/WorkingDir/eclipse/Photomentiel/pictures/");
array_push($prdPossibleRootDir,"E:/EasyPHP-5.3.2i/www/pictures/");
array_push($prdPossibleRootDir,"D:/EasyPHP-5.3.3/www/pictures/");
array_push($prdPossibleRootDir,"D:/test/www/pictures/");
array_push($prdPossibleRootDir,"D:/www/pictures/");
array_push($prdPossibleRootDir,"/var/www/html/pictures/");
for ($i=0;$i<sizeof($prdPossibleRootDir);$i++) {
	if (is_dir($prdPossibleRootDir[$i])){
		$prdRootDir = $prdPossibleRootDir[$i];
	}
}
$EVENTS_TYPES=array('Mariage','Evenement Sportif','Shooting Perso','Autre');
$COMMAND_STATES=array('En attente','Payée','En cours de préparation','Expédiée','Terminée');
$ALBUM_STATES=array('Créé','Prêt','Ouvert','Cloturé');

define('DBTYPE', 'mysql');
if($_SERVER['SERVER_ADDR'] == "213.186.33.16"){
	define('DBHOST', 'mysql5-17.bdb');
	define('DBUSER', 'photomentiel');
	define('DBPWD', 'ajljm2010');
	define('PHOTOGRAPHE_ROOT_DIRECTORY', '/homez.368/photomen/www/pictures/');
}else{
	define('DBHOST', '127.0.0.1');
	define('DBUSER', 'jmguilla');
	define('DBPWD', 'jmguilla');
	define('PHOTOGRAPHE_ROOT_DIRECTORY', $prdRootDir);
}
define('DBMAPS', 'photomentiel');
define('DBPHOTOMENTIEL', 'photomentiel');
define('APPLICATION_ROOT_DIRECTORY', '/');
define('PICTURE_ROOT_DIRECTORY', 'pictures/');
define('THUMB_DIRECTORY', 'thumbs/');
define('PICTURE_DIRECTORY', 'pics/');
define('STRINGID_LENGTH', 8);

define('AUTHOR','Photomentiel');
define('DOMAIN_NAME','photomentiel');
define('DOMAIN_EXT','fr');
define('SHIPPING_RATE',3.50);
define('SHIPPING_RATE_UNTIL',30);
?>