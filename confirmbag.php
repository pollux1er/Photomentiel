<?php
/*
 * confirmbag.php displays the validated content of the bag, format, and number of units per format.
 * One step before payment !
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 5 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
?>
<?php
session_start();
include_once("classes/PMError.class.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Utilisateur.class.php");
include_once("classes/modele/Commande.class.php");
include_once("classes/modele/CommandePhoto.class.php");
include_once("classes/modele/Adresse.class.php");
include_once("classes/modele/AdresseCommande.class.php");

if (!isSet($_SESSION['albumStringID'])){
	photomentiel_die(new PMError("Aucun album spécifié !","Aucun code album n'a été spécifié, que faites vous là ?"));
}

//checked commands
if (!isset($_POST["pictur_0"]) && !isset($_SESSION['COMMAND_LINES'])){
	photomentiel_die(new PMError("Aucune photo commandée !","Aucune photo n'a été commandée, que faites vous là ?"));
}
if (isset($_POST["pictur_0"])){
	//put pictures from POST in SESSION
	$commandLines = array();
	$i=0;
	while (isset($_POST["pictur_$i"])){
		$cl = array('fileName'=>$_POST["pictur_$i"],'formatID'=>$_POST["format_$i"],'quantity'=>$_POST["number_$i"]);
		array_push($commandLines, $cl);
		$i++;
	}
	//$commandLines contains every command lines as it is represented in the session
	$_SESSION['COMMAND_LINES'] = $commandLines;
} else {
	$commandLines = $_SESSION['COMMAND_LINES'];
}

//photos formats
$photoFormatsDim = $_SESSION['photoFormatsDim'];
$photoFormatsPrice = $_SESSION['photoFormatsPrice'];

include("header.php");

if ($utilisateurObj && isset($_POST['payment']) && $_POST['payment'] == 'true'){
	//Manage duplication (F5, history back, etc.)
	$createCommand = false;
	$commandLinesHash = getHashFromCommand($commandLines);
	$postHash = getHashFromArray($_POST);
	if (isset($_SESSION['commandLinesHash']) && isset($_SESSION['commandPostHash'])){
		if ($_SESSION['commandLinesHash'] != $commandLinesHash || $_SESSION['commandPostHash'] != $postHash){
			$_SESSION['commandLinesHash'] = $commandLinesHash;
			$_SESSION['commandPostHash'] = $postHash;
			$createCommand = true;
		}
	} else {
		$_SESSION['commandLinesHash'] = $commandLinesHash;
		$_SESSION['commandPostHash'] = $postHash;
		$createCommand = true;
	}

	if ($createCommand){
		//make command and save it in DB
		$adress = new AdresseCommande();
		if ($_POST['adresses'] == "1"){
			$adresseObj = $utilisateurObj->getAdresse();
			$adress->setNom($adresseObj->getNom());
			$adress->setPrenom($adresseObj->getPrenom());
			$adress->setNomRue($adresseObj->getNomRue());
			$adress->setComplement($adresseObj->getComplement());
			$adress->setCodePostal($adresseObj->getCodePostal());
			$adress->setVille($adresseObj->getVille());
		} else {
			$adress->setNom($_POST['nom']);
			$adress->setPrenom($_POST['prenom']);
			$adress->setNomRue($_POST['adresse1']);
			$adress->setComplement($_POST['adresse2']);
			$adress->setCodePostal($_POST['code_postal']);
			$adress->setVille($_POST['ville']);
		}
	
		//Creation de la commande
		$commande = new Commande();
		$commande->setAdresse($adress);
		$commande->setID_Utilisateur($utilisateurObj->getUtilisateurID());
		$total = 0;
		for ($i=0;$i<sizeof($commandLines) && $total<SHIPPING_RATE_UNTIL;$i++){
			$current = $commandLines[$i];
			$total += $current['quantity']*$photoFormatsPrice[$current['formatID']];
		}
		if ($total < SHIPPING_RATE_UNTIL){
			$commande->setFDP(SHIPPING_RATE);
		}
		//$types = TypePapier::getTypePapiers();
		//$couleurs = Couleur::getCouleurs();
		//add command lines
		for ($i=0;$i<sizeof($commandLines);$i++){
			$currentline = $commandLines[$i];
			$commandePhoto = new CommandePhoto();
			$commandePhoto->setPhoto($currentline['fileName']);
			$commandePhoto->setNombre($currentline['quantity']);
			//$commandePhoto->setID_TypePapier($types[rand(0, (count($types)-1))]->getTypePapierID());
			//$commandePhoto->setID_Couleur($couleurs[rand(0, (count($couleurs)-1))]->getCouleurID());
			$commandePhoto->setID_TaillePapier($currentline['formatID']);
			$commandePhoto->setID_TypePapier(1);
			$commandePhoto->setID_Couleur(1);
			$commandePhoto->setID_Album($_SESSION['albumID']);
			$commandePhoto->setPrix($currentline['quantity']*$photoFormatsPrice[$currentline['formatID']]);
			$commande->addCommandePhoto($commandePhoto);
		}
		//save in DB
		$commande = $commande->create();
		if(!$commande){
			photomentiel_die(new PMError("Erreur lors de la commande !","Un problème est survenu lors de la création de la commande, veuillez réessayer ultérieurement."),false);
		}
		$_SESSION['lastCreatedCommand'] = $commande->getCommandeID();
	} else {
		if (!isset($_SESSION['lastCreatedCommand'])){
			photomentiel_die(new PMError("Erreur lors de la commande !","Une tentative de duplication de la commande a généré un problème."),false);
		}
		$commande = Commande::getCommandeDepuisID($_SESSION['lastCreatedCommand']);
	}
	$cmdConfirmed = true;
} else {
	$cmdConfirmed = false;
}

?>
	<div id="full_content_top">
		Confirmation de votre commande
	</div>
	<div id="full_content_mid">
		<div id="pictures_content">
			<div class="separator10"></div>
			<div class="recap">Voici le récapitulatif de votre commande :</div>
			<table cellspacing="0px">
				<tr id="title">
					<th>Référence</th>
					<th>Format</th>
					<th>Quantité</th>
					<th>Total (&#8364; TTC)</th>
				</tr>
			<?php
				$total = 0;
				$nb_photos = 0;
				for ($i=0;$i<sizeof($commandLines);$i++){
					$current = $commandLines[$i];
					$imp = ($i%2==0)?'pair':'impair';
					echo '<tr>';
					//ref
					echo '<td class="'.$imp.'">'.substr($current['fileName'],0,sizeof($current['fileName'])-5).'</td>';
					//format
					echo '<td class="'.$imp.'">'.$photoFormatsDim[$current['formatID']].'</td>';
					//quantity
					echo '<td class="'.$imp.'">'.$current['quantity'].'</td>';
					$nb_photos += $current['quantity'];
					//total
					$partial = $current['quantity']*$photoFormatsPrice[$current['formatID']];
					$total += $partial;
					echo '<td class="'.$imp.'">'.sprintf('%.2f',$partial).' &#8364;</td>';
					echo '</tr>';
				}
				echo '<tr id="total_"><td style="background-color:white;"></td><td align="right">Total photos :</td><td>'.$nb_photos.'</td><td>'.sprintf('%.2f',$total).' &#8364;</td></tr>';
				if ($total < SHIPPING_RATE_UNTIL){
					$ship_rate = sprintf('%.2f',SHIPPING_RATE).' &#8364';
					$total += SHIPPING_RATE;
				} else {
					$ship_rate = '<span style="color:darkgreen;text-decoration:underline;">Offert !</span>';
				}
				echo '<tr id="total_"><td colspan="2" style="background-color:white;"></td><td align="right">Frais de port :</td><td>'.$ship_rate.'</td></tr>';
				echo '<tr id="total"><td colspan="2" style="background-color:white;"></td><td align="right">Total :</td><td>'.sprintf('%.2f',$total).' &#8364;</td></tr>';
			?>
			</table>
			<div class="separator10"></div>
		</div>
		<div id="adresses_content">
			<div id="make_cmd">
				<?php
					if (!$utilisateurObj){
				?>
						<ul>
							<br/>
							<b>Afin de poursuivre votre commande, vous devez vous identifier ou créer un compte :</b>
							<br/><br/>
							<li>Si vous venez de créer un compte, veuillez l'activer en suivant le lien qui vous a été envoyé par E-mail, puis connectez vous en utilisant les champs ci-dessus.</li>
							<li>Pour vous connecter à votre compte, veuillez vous identifier en utilisant les champs ci-dessus, sous la bannière.</li>
							<li>Pour créer un compte, <a href="adduser.php?type=cl&np=confirmbag.php">cliquez ici.</a></li>
						</ul>
						<div class="separator10" style="height:50px"></div>
				<?php
					} else {
						
						$adresseObj = $utilisateurObj->getAdresse();
						
						if ($cmdConfirmed){
				?>
							<div class="separator10"></div>
							<div class="recap_info">
								Vous avez commandé <i><b><?php echo $nb_photos; ?> photo<?php echo $nb_photos==1?'':'s'; ?></i></b> pour un total de 
								<i><b><?php echo sprintf('%.2f',$total); ?> &#8364;</i></b>.<br/><br/>
								Vos photos vous seront livrées à l'adresse suivante : <br/><br/>
								<div class="adr_b" style="font-size:14px;">
									<?php
										if ($_POST['adresses'] == "1"){
											$adresseObj = $utilisateurObj->getAdresse();
											echo $adresseObj->getNom()." ".$adresseObj->getPrenom()."<br/>";
											echo $adresseObj->getNomRue()."<br/>";
											if ($adresseObj->getComplement() != null && $adresseObj->getComplement() != ''){
												echo $adresseObj->getComplement()."<br/>";
											}
											echo $adresseObj->getCodePostal()." ".$adresseObj->getVille()."<br/>";
											echo 'France';
										} else {
											echo $_POST['nom']." ".$_POST['prenom']."<br/>";
											echo $_POST['adresse1']."<br/>";
											if ($_POST['adresse2'] != ''){
												echo $_POST['adresse2']."<br/>";
											}
											echo $_POST['code_postal']." ".$_POST['ville']."<br/>";
											echo 'France';
										}
									?>
								</div>
								<br/>
								Veuillez choisir un moyen de paiement (<i>ceci vous conduira sur la page sécurisée de paiement</i><img src="e-transactions/payment/logo/CLEF.gif"/>) <br/><br/>
								<?php
									$_SESSION['last_command'] = $commande->getCommandeID();
									include("e-transactions/selectcard.php");
									displayCards(null,toBankAmount($total),null,$utilisateurObj->getUtilisateurID(),$commande->getCommandeID());
								?>
							</div>
				<?php
						} else {
				?>
							<u>Veuillez choisir votre adresse de livraison :</u>
							<div class="separator10"></div>
							<form id="adress_selection" method="POST" action="confirmbag.php">
								<div id="adress_left">
									<input id="main_adr" type="radio" name="adresses" value="1" checked="true"/> Utiliser mon adresse - (<a href="adduser.php?np=confirmbag.php">modifier mon adresse</a>)<br/>
									<br/>
									<div class="adr_b">
									<?php
										echo $adresseObj->getNom()." ".$adresseObj->getPrenom()."<br/>";
										echo $adresseObj->getNomRue()."<br/>";
										if ($adresseObj->getComplement() != null && $adresseObj->getComplement() != ''){
											echo $adresseObj->getComplement()."<br/>";
										}
										echo $adresseObj->getCodePostal()." ".$adresseObj->getVille()."<br/>";
										echo 'France';
									?>
									</div>
								</div>
								<div id="adress_separator"></div>
								<div id="adress_right">
									<input id="main_adr2" type="radio" name="adresses" value="2"/> Utiliser une autre adresse<br/>
									<br/>
									<div class="adr_b">
										<table>
											<tr>
												<td>
													Nom : 
												</td><td>
													<input name="nom" class="textfield" type="text" id="nom" required="required"/>
												</td><td>
													<div  class="checkform" id="rnom"></div>
												</td>
											</tr>
											<tr>
												<td>
													Prénom : 
												</td><td>
													<input name="prenom" class="textfield" type="text" id="prenom" required="required"/>
												</td><td>
													<div  class="checkform" id="rprenom"></div>
												</td>
											</tr>
											<tr>
												<td>
													Adr. (numéro + rue) : 
												</td><td>
													<input name="adresse1" class="textfield" type="text" id="adresse1" required="required"/>
												</td><td>
													<div  class="checkform" id="radresse1"></div>
												</td>
											</tr>
											<tr>
												<td>
													Compl. (Bât, Entrée) : 
												</td><td>
													<input name="adresse2" class="textfield" type="text" id="adresse2"/>
												</td><td>
													<div  class="checkform" id="radresse2"></div>
												</td>
											</tr>
											<tr>
												<td>
													Code postal : 
												</td><td>
													<input name="code_postal" class="textfield" type="text" id="code_postal" maxlength="5" exactlength="5" regexp="^[0-9]+$" required="required"/>
												</td><td>
													<div  class="checkform" id="rcode_postal"></div>
												</td>
											</tr>
											<tr>
												<td>
													Ville : 
												</td><td>
													<input name="ville" class="textfield" type="text" id="ville" required="required"/>
												</td><td>
													<div  class="checkform" id="rville"></div>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="separator10" style="height:20px"></div>
								<input type="hidden" name="payment" value="true"/>
								<center>
									<input type="button" class="button" value="Retour" onClick="history.back();"/>
									<input type="submit" class="button" value="Continuer" id="valid_button"/>
								</center>
							</form>
							<div class="separator10"></div>
				<?php
						}
					}
				?>
			</div>
		</div>
	</div>
	<div id="full_content_bot"></div>
<?php
include("footer.php");
?>