<?php
$dir_photographedao_class_php = dirname(__FILE__);
include_once $dir_photographedao_class_php . "/../../Config.php";
include_once ($dir_photographedao_class_php . "/UtilisateurDAO.class.php");
include_once ($dir_photographedao_class_php . "/ModeleDAOUtils.class.php");
include_once $dir_photographedao_class_php . "/../../controleur/ControleurUtils.class.php";

class PhotographeDAO extends UtilisateurDAO{
	public function __construct() {
		parent::__construct();
	}

	public function validContrat($photographe){
		$query = "update Photographe set isReady = true where photographeID = " .
		mysql_real_escape_string($photographe->getPhotographeID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$photographe->setIsReady(true);
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * pour augmenter de 1 le nombre openFTP,
	 * retourne true/false et gere les transaction
	 */
	public function incOpenFTP($ph){
		$current = $ph->getOpenFTP();
		$next = $current + 1;
		$query = "update Photographe set openftp = " .
		mysql_real_escape_string($next) . " where photographeID = " .
		mysql_real_escape_string($ph->getPhotographeID()) . " and openftp = " .
		mysql_real_escape_string($current);
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$ph->setOpenFTP($next);
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * pour diminuer de 1 le nombre openFTP,
	 * retourne true/false et gere les transaction
	 */
	public function decOpenFTP($ph){
		$current = $ph->getOpenFTP();
		$next = $current - 1;
		if($next < 0){
			return;
		}
		$query = "update Photographe set openftp = " .
		mysql_real_escape_string($next) . " where photographeID = " .
		mysql_real_escape_string($ph->getPhotographeID()) . " and openftp = " .
		mysql_real_escape_string($current);
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$ph->setOpenFTP($next);
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * Renvoie un photographe au hasard
	 */
	public function getPhotographeAleatoire(){
		$query = "select * from Photographe as p, Utilisateur as u, Adresse as a where p.id_utilisateur = u.utilisateurID and a.id_utilisateur = u.utilisateurID order by rand() limit 1";
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildUtilisateurFromRow");
	}
	/**
	 * Renvoie le photographe avec l'id fourni
	 */
	public function getPhotographeDepuisID($id){
		$query = "select * from Photographe as p, Utilisateur as u, Adresse as a where p.id_utilisateur = u.utilisateurID and a.id_utilisateur = u.utilisateurID and p.photographeID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildUtilisateurFromRow");		
	}

	public function getPhotographes($actif = true){
		$query = "select * from Photographe as p, Utilisateur as u, Adresse as a where p.id_utilisateur = u.utilisateurID and a.id_utilisateur = u.utilisateurID";
		if($actif){
			$query .=" and u.actif = true";
		}
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildUtilisateurFromRow");
	}

	public function lockTableCreate(){
		$query = "lock tables Photographe write, Utilisateur write, Activate write, Adresse write";
		$tmp = $this->update($query);
		if($tmp){
			return true;
		}else{
			return false;
		}
	}

	public function unlockTable(){
		$query = "unlock tables";
		$tmp = $this->update($query);
		if($tmp){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * cree le photographe passe en parametre en BD et le
	 * retourne avec ses champs mis a jour.
	 * @param unknown_type $photographe
	 */
	public function create($photographe, $activateID){
		$dir_photographedao_class_php = dirname(__FILE__);
		include_once $dir_photographedao_class_php . "/AdresseDAO.class.php";
		include_once $dir_photographedao_class_php . "/../Adresse.class.php";
		include_once $dir_photographedao_class_php . "/../CreateException.class.php";
		$email = $photographe->getEmail();
		//controle de l'email.
		if(!$this->controleEmail($email)){
			throw new CreateUtilisateurException("Email already in use");
		}

		try{
			if(!$this->lockTableCreate()){
				$this->unlockTable();
				ControleurUtils::addError("Impossible de locker tables a la creation photographe", true);
				return false;
			}
			$this->startTransaction();
			//creation de l'utilisateur
			$utilisateur = $this->createUtilisateur($photographe, $activateID);
	
			if(!$utilisateur){
				$this->rollback();
				if(!$this->unlockTable()){
					ControleurUtils::addError("Unlock table error, creation photographe, impossible de creer utilisateur", true);
				}
				throw new CreateUtilisateurException("Cannot get the newly created user.");
			}
			$photographe->setUtilisateurID($utilisateur->getUtilisateurID());
			//creation du photographe
			$photographe = $this->createPhotographe($photographe);
			if(!$photographe){
				$this->rollback();
				if(!$this->unlockTable()){
					ControleurUtils::addError("Unlock table error, creation photographe, impossible de creer photographe", true);
				}
				throw new CreateUtilisateurException("Cannot create the photographe.");
			}
	
			$adresse = $photographe->getAdresse();
			if(isset($adresse)){
				$adresse->setID_Utilisateur($photographe->getUtilisateurID());
				if(0 < $adresse->getAdresseID()){
					$adao = new AdresseDAO();
					$adresse = $adao->save($adresse);
					if(!$adresse){
						$this->rollback();
						if(!$this->unlockTable()){
							ControleurUtils::addError("Unlock table error, creation photographe, impossible de sauver adresse", true);
						}
						throw new CreateUtilisateurException("Impossible de sauver la nouvelle adresse.");
					}
				}else{
					$adao = new AdresseDAO();
					$adresse = $adao->create($adresse);
					if(!$adresse){
						$this->rollback();
						if(!$this->unlockTable()){
							ControleurUtils::addError("Unlock table error, creation photographe, impossible de creer adresse", true);
						}
						throw new CreateUtilisateurException("Impossible de creer la nouvelle adresse.");
					}
				}
			}
			$this->commit();
			if(!$this->unlockTable()){
				ControleurUtils::addError("Unlock table error, creation photographe", true);
			}
			return $photographe;
		}catch(Exception $exception){
			$this->rollback();
			if(!$this->unlockTable()){
				ControleurUtils::addError("Unlock table error, creation photographe sur catch", true);
			}
			return false;
		}
	}

	/**
	 * sauve le parametre en BD.
	 * Retourne true en cas de succes, false sinon.
	 * @param Utilisateur $utilisateur
	 */
	public function save($photographe){
		$photographe->setIBAN(ModeleDAOUtils::Rib2Iban($photographe->getRIB_b(), $photographe->getRIB_g(), $photographe->getRIB_c(), $photographe->getRIB_k()));
		$adresse = $photographe->getAdresse();
		$query = "update Utilisateur, Photographe, Adresse set nom = '" .
		mysql_real_escape_string($adresse->getNom()) . "', prenom = '" .
		mysql_real_escape_string($adresse->getPrenom()) . "', nomRue = '" .
		mysql_real_escape_string($adresse->getNomRue()) . "', complement = '" .
		mysql_real_escape_string($adresse->getComplement()) . "', ville = '" .
		mysql_real_escape_string($adresse->getVille()) . "', codePostal = '" .
		mysql_real_escape_string($adresse->getCodePostal()) . "', nomEntreprise = '" .
		mysql_real_escape_string($photographe->getNomEntreprise()) . "', siren = '" . 
		mysql_real_escape_string($photographe->getSiren()) . "', telephone = '" .
		mysql_real_escape_string($photographe->getTelephone()) . "', siteWeb = '" .
		mysql_real_escape_string($photographe->getSiteWeb()) . "', rib_b = '" .
		mysql_real_escape_string($photographe->getRIB_b()) . "', rib_g = '" .
		mysql_real_escape_string($photographe->getRIB_g()) . "', rib_c = '" . 
		mysql_real_escape_string($photographe->getRIB_c()) . "', rib_k = '" .
		mysql_real_escape_string($photographe->getRIB_k()) . "', bic = '" . 
		mysql_real_escape_string($photographe->getBIC()) . "', iban = '" .
		mysql_real_escape_string($photographe->getIBAN()) . "', pourcentage = " .
		mysql_real_escape_string($photographe->getPourcentage()) . ", isTelephonePublique = ";
		if($photographe->isTelephonePublique()){
			$query .= "true";
		}else{
			$query .= "false";
		}
		$query .= ", TVA = " .
		mysql_real_escape_string($photographe->getTVA()) . " where Utilisateur.utilisateurID = " . 
		"Photographe.id_utilisateur and Adresse.id_utilisateur = Utilisateur.utilisateurID and Utilisateur.utilisateurID = " .
		mysql_real_escape_string($photographe->getUtilisateurID()) . " and Photographe.photographeID = " .
		mysql_real_escape_string($photographe->getPhotographeID());
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return $photographe;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function voter($photographe, $note){
		$currentNote = $photographe->getNote();
		$nombreVotant = $photographe->getNombreVotant();
		$newNombreVotant = $nombreVotant + 1;
		$newNote = ($currentNote * $nombreVotant + $note) / $newNombreVotant;
		$query = "update Photographe set nombreVotant = " .
		mysql_real_escape_string($newNombreVotant) . ", note = " .
		mysql_real_escape_string($newNote) . " where photographeID = " .
		mysql_real_escape_string($photographe->getPhotographeID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp){
			$photographe->setNombreVotant($newNombreVotant);
			$photographe->setNote($newNote);
			$this->commit();
			return $photographe;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**###########################################
	 * Helpers
	 ############################################*/
	/**
	 * Pour effectivement creer le photographe en BD
	 * @param string $ne nom entreprise
	 * @param string $siren #siren
	 * @param string $tel telephone
	 * @param string $web site web
	 * @param string $utilisateur idutilisateur
	 * @param string $rib #rib
	 */
	protected function createPhotographe($photographe){
		$photographe->setIBAN(ModeleDAOUtils::Rib2Iban($photographe->getRIB_b(), $photographe->getRIB_g(), $photographe->getRIB_c(), $photographe->getRIB_k()));
		$ne = $photographe->getNomEntreprise();
		$siren = $photographe->getSiren();
		$tel = $photographe->getTelephone();
		$web = $photographe->getSiteWeb();
		$rib_b = $photographe->getRIB_b();
		$rib_g = $photographe->getRIB_g();
		$rib_c = $photographe->getRIB_c();
		$rib_k = $photographe->getRIB_k();
		$tva = $photographe->getTVA();
		$isTelPub = $photographe->isTelephonePublique();
		$uid = $photographe->getUtilisateurID();
		$bic = $photographe->getBIC();
		$iban = $photographe->getIBAN();
		$pourcentage = $photographe->getPourcentage();
		$hometmp = date('Ymd');
		$query = "select count(*) as num from Photographe where home like '" . $hometmp . "%'";
		$tmp = $this->retrieve($query);
		if(!$tmp){
			return false;
		}
		$homeDelta = 0;
		foreach($tmp as $count){
			$homeDelta = $count['num'];
			break;
		}
		$home = $hometmp . sprintf("%02d", $homeDelta);
		$query = "insert into Photographe(TVA, isTelephonePublique, nomEntreprise, siren, telephone, siteWeb, home, pourcentage, id_utilisateur, rib_b, rib_g, rib_c, rib_k, bic, iban) values (".
		mysql_real_escape_string($tva) . ", ";
		if($isTelPub){
			$query .= "true";
		}else{
			$query .= "false";
		}
		$query .= ", '" .
		mysql_real_escape_string($ne) . "', '" . 
		mysql_real_escape_string($siren) . "', '" . 
		mysql_real_escape_string($tel) . "', '" . 
		mysql_real_escape_string($web) . "', '" . 
		mysql_real_escape_string($home) . "', " .
		mysql_real_escape_string($pourcentage) . ", " .
		mysql_real_escape_string($uid) . ", '" .
		mysql_real_escape_string($rib_b) . "', '" .
		mysql_real_escape_string($rib_g) . "', '" .
		mysql_real_escape_string($rib_c) . "', '" .
		mysql_real_escape_string($rib_k) . "', '" .
		mysql_real_escape_string($bic) . "', '" .
		mysql_real_escape_string($iban) . "')";
		$tmp = $this->retrieve($query);
		if(!$tmp){
			return false;
		}
		$photographe->setPhotographeID($this->lastInsertedID());
		return $photographe;	
	}
}
?>
