<?php
$dir_administration_controleur_album_php = dirname(__FILE__);
include_once $dir_administration_controleur_album_php . "/../../classes/modele/Album.class.php";

switch($action){
	case detail_album:
		include $dir_administration_controleur_album_php . "/../header.php";
		echo '<form action="dispatcher.php" method="post"><input type="hidden" name="action" value="montrer_album"/><input type="submit" value="retour liste album"/></form>' . "\n";
		
		echo '<form action="dispatcher.php" method="post"><input type="hidden" name="action" value="montrer_album"/><input type="submit" value="retour liste album"/></form>' . "\n";
		include $dir_administration_controleur_album_php . "/../footer.php";
	exit();
	case montrer_album:
		include_once $dir_administration_controleur_album_php . "/../../classes/modele/Evenement.class.php";
		include $dir_administration_controleur_album_php . "/../header.php";
		$assocs = Album::getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates(0, NULL, NULL, false, 2);
		if($assocs){
			echo '<form action="album.php"><input type="submit" value="retour gestion albums"/></form>' . "\n";
			echo "<table><tr><td>id</td><td>nom album</td><td>photographe</td><td>gain total</td></tr>";
			foreach($assocs as $assoc){
				$album = $assoc['Album'];
				$photo = $assoc['Photographe'];
				$stringid = $assoc['StringID'];
				echo '<tr><td>#' . $album->getAlbumID() . ' - </td><td> <a target="_blank" href="../viewalbum.php?al=' . $stringid->getStringID() . '">' . $album->getNom() . '</a> </td><td>' . $photo->getAdresse()->getPrenom() . " - " . $photo->getAdresse()->getNom() . ' </td><td> ' . $album->getGainTotal() . ' </td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="detail_album"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" value="détails"/></form></td></tr>' . "\n";
			}
			echo "</table>";
			echo '<form action="album.php"><input type="submit" value="retour gestion albums"/></form>' . "\n";
		}else{
			echo "Aucun!";
		}
		include $dir_administration_controleur_album_php . "/../footer.php";
		exit();
	break;
	case valider_album:
		$albums = Album::getNDerniersAlbums(0, false, 1);
		$listeAlbum = array();
		foreach($albums as $album){
			if(isset($_POST['albumID' . $album->getAlbumID()])){
				$listeAlbum[] = $album;
			}
		}
		$result = Album::validerListeAlbum($listeAlbum);
		if($result){
			$_SESSION['message'] .= "Liste d'album validée<br/>";
		}else{
			$_SESSION['message'] .= "Un problème est survenue pendant la validation de la liste d'album<br/>";
		}
	break;
	case activer_album:
		$albums = Album::getNDerniersAlbums(0, false, 3);
		$listeAlbum = array();
		foreach($albums as $album){
			if(isset($_POST['albumID' . $album->getAlbumID()])){
				$listeAlbum[] = $album;
			}
		}
		$result = Album::activerListeAlbum($listeAlbum);
		if($result){
			$_SESSION['message'] .= "Liste d'album activée<br/>";
		}else{
			$_SESSION['message'] .= "Un problème est survenue pendant l'activation de la liste d'album<br/>";
		}
	break;
	default:
		$_SESSION['message'] .= "action inconnue dans controleur_album " . $action . "<br/>";
	break;
}
header('Location: album.php');
exit();
?>