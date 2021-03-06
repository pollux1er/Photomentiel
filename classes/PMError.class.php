<?php
/*
 * PMError.php displays an error using parameters given argument.
 * It displays the error message in the photomentiel context view.
 * 
 * Including this file in a script and calling the photomentiel_die() method causes the caller to be stopped

 * Author : SCHIOUFF (All rights reserved)
 * 
 * Created on 12 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */

class PMError {
	
	private $title;
	private $message;

	public function __construct($title="Titre", $message="Message") {
		$this->title = $title;
		$this->message = $message;
	}

	public function getTitle(){
		return $this->title;
	}

	public function setTitle($title){
		$this->title = $title;
	}

	public function getMessage(){
		return $this->message;
	}

	public function setMessage($msg){
		$this->message = $msg;
	}

}

function photomentiel_die($pmError,$declareHeader = true){
	if ($declareHeader){	
		include("header.php");
	}
	?>
	<div id="full_content_top">
		<?php echo $pmError->getTitle(); ?>
	</div>
	<div id="full_content_mid">
		<div class="separator10" style="height:80px;"></div>
		<div id="error">
			<div id="title">
				<?php echo $pmError->getTitle(); ?>
			</div>
			<div id="message">
				<?php echo $pmError->getMessage(); ?>
			</div>
		</div>
		<div class="separator10" style="height:50px;"></div>
		<center>
			<input class="button" style="margin-right:25px;width:220px;height:35px;" type="button" value="Revenir à la page précédente" onClick="history.back();" />
			<input class="button" style="margin-left:25px;width:190px;height:35px;" type="button" value="Revenir à l'accueil" onClick="document.location.href='index.php'" />
		</center>
		<div class="separator10" style="height:80px;"></div>
	</div>
	<div id="full_content_bot"></div>
	<?php
	include("footer.php");
	die();
}
?>
