<?php
$dir_administration_index_php = dirname(__FILE__);
include $dir_administration_index_php . "/header.php";
?>
<div>
Rendez-vous dans la section désirée:<br/>
<form method="post" action="commande.php">
	<input type="submit" value="Gestion Commandes"/>
</form>
<form method="post" action="album.php">
	<input type="submit" value="Gestion Albums"/>
</form>
<form method="post" action="evenement.php">
	<input type="submit" value="Gestion Evenements"/>
</form>
</div>
<?php 
include $dir_administration_index_php . "/header.php";
?>