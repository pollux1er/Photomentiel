<?php

class ModeleDAOUtils{

	public static function Rib2Iban($codebanque,$codeguichet,$numerocompte,$cle){
		$charConversion = array("A" => "10","B" => "11","C" => "12","D" => "13","E" => "14","F" => "15","G" => "16","H" => "17",
		"I" => "18","J" => "19","K" => "20","L" => "21","M" => "22","N" => "23","O" => "24","P" => "25","Q" => "26",
		"R" => "27","S" => "28","T" => "29","U" => "30","V" => "31","W" => "32","X" => "33","Y" => "34","Z" => "35");
	 
		$tmpiban = strtr($codebanque.$codeguichet.$numerocompte.$cle."FR00",$charConversion);
	 
		// Soustraction du modulo 97 de l'IBAN temporaire � 98
		$cleiban = strval(98 - intval(bcmod($tmpiban,"97")));
	 
		if (strlen($cleiban) == 1)
			$cleiban = "0".$cleiban;
	 
		return "FR".$cleiban.$codebanque.$codeguichet.$numerocompte.$cle;
	}
}