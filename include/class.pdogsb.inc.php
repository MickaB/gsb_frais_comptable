<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Michael B
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=gsb_frais';   		
      	private static $user='root' ;    		
      	private static $mdp='' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @param $utilisateur : visiteur ou comptable
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosUtilisateur($login, $mdp, $utlisateur){
		$req = "select $utlisateur.id as id, $utlisateur.nom as nom, $utlisateur.prenom as prenom from $utlisateur 
		where $utlisateur.login='$login' and $utlisateur.mdp='$mdp'";
		$res = PdoGsb::$monPdo->query($req);
		$ligne = $res->fetch();
		return $ligne;
	}

/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait 
		where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois'  
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais) {
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = $qte
			where lignefraisforfait.idvisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idfraisforfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
		
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
/**
 * Retourne les mois pour lesquelles il y a des fiches de frais
*/	
	public function obtenirMoisVisiteur() {
			$req = "select distinct fichefrais.mois as mois from  fichefrais order by fichefrais.mois desc ";
			$res = PdoGsb::$monPdo->query($req);
			$laLigne = $res->fetch(PDO::FETCH_ASSOC);
			return $laLigne;
	}
/**
 * Retourne l'id nom et prenom des visiteur sous forme de tableau
*/	
	function obtenirReqIdFicheFrais() {
			$req = "select visiteur.nom as nom, visiteur.id as id, visiteur.prenom as prenom from visiteur";
			$res = PdoGsb::$monPdo->query($req);
			$visiteur = $res->fetchAll();
			return $visiteur;
	}
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values('','$idVisiteur','$mois','$libelle','$dateFr','$montant')";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais 
		where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		
		while ($laLigne = $res->fetch())	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
				"mois"=>"$mois",
				"numAnnee"  => "$numAnnee",
				"numMois"  => "$numMois"
				);
		}
		return $lesMois;
	}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select fichefrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update fichefrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->query($req);
	}
        /**
 * Retourne les informations d'un visiteur

 * @param $id : id du visiteur
 * @return le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getNomUtilisateur($id){
		$req = "select visiteur.nom as nom, visiteur.prenom as prenom from visiteur 
		where visiteur.id='$id'";
		$res = PdoGsb::$monPdo->query($req);
		$ligne = $res->fetch();
		return $ligne;
	}
	/**
 * L'utilisateur demande à suivre le paiement des fiches de frais
 * Sélectionner id_etat pour la vailidation et mises en paiment (VA)
 * @return la liste des fiche de frais valider
 */
 
	public function getValidationFicheFrais($visiteur, $mois)
	{
		$req = "select * from fichefrais where fichefrais.idEtat!='RB' and fichefrais.idVisiteur='$visiteur' and fichefrais.mois='$mois'";
		$res = PdoGsb::$monPdo->query($req);
                $ligne = $res->fetch();
		return $ligne;
	}
       
 /**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
    public function majFicheFraisRembourse($idVisiteur,$mois){
            $req = "update fichefrais set idEtat = 'RB', dateModif = now() 
            where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
            PdoGsb::$monPdo->query($req);
    }
 /**
 * Modifie la ligne frais pour le mois et visiteur
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm et type
 */
 
    public function majLigneFrais($idVisiteur,$mois, $type, $quantite){
            $req = "update lignefraisforfait set quantite = '$quantite'
            where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois = '$mois' and lignefraisforfait.idFraisForfait='$type'";
            PdoGsb::$monPdo->query($req);
    }
/**
 * affiche les visiteurs pour lesquel ils sont validés
 */
     public function getLesVisiteursDisponibles(){
            $req2 = "select distinct(id,prenom) from visiteur inner join fichefrais on visiteur.id = fichefrais.idVisiteur where idEtat ='VA'";
            $res2 = PdoGsb::$monPdo->query($req2);
            $lesVisiteur =array();
            $laLigne = $res2->fetch();
            while($laLigne != null)	
            {
               $id = $laLigne['id']; 
               $lesVisiteur["$id"]=array(
               "id"=>"$id");
               $laLigne = $res2->fetch(); 
            }
            return $lesVisiteur;
        }
 /**
 * Affiche les mois pour lesquelles il y a des fiches validés
 */   
        public function getLesMoisDisponiblesVisiteurValide() {
                $req = "select distinct fichefrais.mois as mois,fichefrais.dateModif,fichefrais.montantValide,fichefrais.idEtat from fichefrais Where fichefrais.idEtat='VA' 
                        OR 
                        (fichefrais.idEtat='VA' AND  MONTH(CURRENT_DATE)-MONTH(dateModif)<=4 AND CURRENT_DATE-dateModif<1)";
		$res = PdoGsb::$monPdo->query($req);
                $lesMois =array();
                $laLigne = $res->fetch();
 		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
                        $dateMofid = $laLigne['dateModif'];
                        $montantValide = $laLigne['montantValide'];
                        $lesMois[$mois]=array("mois"=>"$mois","numAnnee"=>$numAnnee,"numMois"=>$numMois,"dateModif"=>$dateMofid,"montantValide"=>$montantValide);
                        $laLigne = $res->fetch(); 
                }                
                return $lesMois;          
       }  
         
       
 /**
 * Affiche le montant et la date d'une fiche
 
 * @param $levisiteur 
 * @param $leMois sous la forme aaaamm et type
 */        
        public function getLesInfosFrais($levisiteur, $leMois) {
                $req = "select fichefrais.montantValide, fichefrais.dateModif from fichefrais where idVisiteur=\"".$levisiteur."\" and mois=\"".$leMois."\";";
		$res = PdoGsb::$monPdo->query($req);
                $lesinfos =array();
                $laLigne = $res->fetch();
 		while($laLigne != null)	{
			$montantValide = $laLigne['montantValide'];
                        $dateModif= $laLigne['dateModif'];
                        $lesinfos[$montantValide]=array("montantValide"=>$montantValide,"dateModif"=>$dateModif);
                        $laLigne = $res->fetch(); 
                }     
                print_r($lesinfos);
                return $lesinfos;     
        }
         
  /**
 * Retourne les détails d'un visiteur pour lequelle une fiche a été validé

 */
        public function getInfosVisiteurValide(){
           
            $requete = "Select id,nom,prenom,mois,idEtat 
                        from visiteur Inner join fichefrais on fichefrais.idVisiteur = visiteur.id 
                        Where fichefrais.idEtat='VA' 
                        OR 
                        (fichefrais.idEtat='RB' AND  MONTH(CURRENT_DATE)-MONTH(dateModif)<=4 AND CURRENT_DATE-dateModif<1)";
            $rs1 = PdoGsb::$monPdo->query($requete);
            $ensembleUtilisateur = array();
            $laLigne = $rs1->fetch();
            while ($laLigne != null)
            {
                $utilisateur = $laLigne['id'];
                $utilisateurNom = $laLigne['nom'];
                $utilisateurPrenom = $laLigne['prenom'];
                $ensembleUtilisateur[$utilisateur]=array("id"=>$utilisateur,"nom"=>$utilisateurNom,"prenom"=>$utilisateurPrenom);
                $laLigne = $rs1->fetch();
            }
            return $ensembleUtilisateur;           
       }   
       
/**
 * Modifie la quantité pour une fiche de frais
 
 * @param $lidVisiteur 
 * @param $mois sous la forme aaaamm et type
 * @param $quantite
 * @param $idFraisForfait
 */ 
        public function majFraisForfaitValide($idVisiteur, $mois, $quantite, $idFraisForfait)
        {
            $ligne = '';
            if(is_numeric($quantite)){
            $req = "UPDATE lignefraisforfait SET quantite = '$quantite' 
                    WHERE lignefraisforfait.idvisiteur ='$idVisiteur' AND lignefraisforfait.mois = '$mois' 
                    AND lignefraisforfait.idFraisForfait='$idFraisForfait'";
            PdoGsb::$monPdo->query($req);
            
                $ligne = 'ok';
            }
            return $ligne;
        }
   /**
    * Modifie l'état et la date de modification d'une fiche de frais

    * Modifie le champ idEtat et met la date de modif à aujourd'hui
    * @param $idVisiteur 
    * @param $mois sous la forme aaaamm
    */
 
        public function majFicheFraisValide($idVisiteur,$mois,$montant){
                $req = "update fichefrais set idEtat = 'VA', dateModif = now(), montantValide = '$montant' 
                where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
                PdoGsb::$monPdo->query($req);
        }
        public function getValeurForfaitEtape()
        {
            $requete10 = "select montant from fraisforfait where libelle ='Forfait Etape'";
            $query = PdoGsb::$monPdo->query($requete10) ;
            $data = $query->fetch();
            return $data['montant'];    
        }
        public function getValeurFraisKilométrique()
        {
            $requete11 = "select montant from fraisforfait where libelle ='Frais Kilométrique'";
            $query = PdoGsb::$monPdo->query($requete11) ;
            $data = $query->fetch();
            return $data['montant'];    
        }
        public function getValeurForfaitNuiteHotel()
        {
            $requete7 = "select montant from fraisforfait where libelle ='Nuitée Hôtel'";
            $query = PdoGsb::$monPdo->query($requete7) ;
            $data = $query->fetch();
            return $data['montant'];    
        }
        public function getValeurForfaitNuiteRestaurant()
        {        
            $requete8 = "select montant from fraisforfait where libelle ='Repas Restaurant'";
            $query = PdoGsb::$monPdo->query($requete8) ;
            $data = $query->fetch();
            return $data['montant'];           
        }
        public function getValeurFraisEtapeUtilisateur($visiteur,$mois)
        
        {        
            $requete12 = "select quantite from lignefraisforfait where idFraisForfait = 'ETP' and mois = '$mois'  and idVisiteur = '$visiteur'";
            $query = PdoGsb::$monPdo->query($requete12) ;
            $data = $query->fetch();
            return $data['quantite'];             
        }
        public function getValeurFraisKilometriqueUtilisateur($visiteur,$mois)
        {
            $requete13 = "select quantite from lignefraisforfait where idFraisForfait = 'KM' and mois = '$mois'  and idVisiteur = '$visiteur'";
            $query = PdoGsb::$monPdo->query($requete13) ;
            $data = $query->fetch();
            return $data['quantite'];
        }
        public function getValeurFraisNuiteHotelUtilisateur($visiteur,$mois)
        {
            $requete14 = "select quantite from lignefraisforfait where idFraisForfait = 'NUI' and mois = '$mois'  and idVisiteur = '$visiteur'";
            $query = PdoGsb::$monPdo->query($requete14) ;
            $data = $query->fetch();
            return $data['quantite'];
        }
        public function getValeurFraisRepasRestaurantUtilisateur($visiteur,$mois)
        {
            $requete15 = "select quantite from lignefraisforfait where idFraisForfait = 'REP' and mois = '$mois'  and idVisiteur = '$visiteur'";
            $query = PdoGsb::$monPdo->query($requete15) ;
            $data = $query->fetch();
            return $data['quantite'];
        }
         public function MiseAjourMontantValide($montant,$visiteur,$mois)
         {
            $requete16 ="UPDATE fichefrais set montantValide='$montant' where fichefrais.idVisiteur= '$visiteur' and fichefrais.mois='$mois'";
            PdoGsb::$monPdo->exec($requete16);
         }
         public function getValeurFraisHorsForfaitUtilisateur($visiteur,$mois)
         {
            $requete16 ="select sum(montant)as lemontant from lignefraishorsforfait where idVisiteur = '$visiteur' and mois='$mois'";
            $query = PdoGsb::$monPdo->query($requete16) ;
            $data = $query->fetch();
            return $data['lemontant'];
         }
  /**
 * Modifie la ligne frais hors forfait reporter d'un mois
 
 * @param $id 
 */
 
    public function majLigneFraisHForfait($id){
            
            $mois = "select mois from lignefraishorsforfait where id = '$id'";
            $mois = PdoGsb::$monPdo->query($mois);
            $numAnnee = substr($mois, 0,4);
            $numeroMois = substr($mois, 4,2);
            if($numeroMois == '12'){
                $numAnnee = $numAnnee+1;
                $numeroMois = '01';
                $mois = $numAnnee.$numeroMois;
            }
            else{
                $mois = $mois + 1;
            }                    
            $req = "update lignefraishorsforfait set mois ='$mois' where id = '$id'";
            PdoGsb::$monPdo->query($req);
    }
       
        /**
 * Modifie les lignes des frais Hors forfait
 * pour chaque idFrais du tableau $lesFraisHF
 * si la valeur est 'S' supprime cette ligne
 * si la valeur est 'R' modifie cette ligne
 * si la valeur est 'V' ne fait rien
 * c�d ajoute un mois au champ 'mois'
 * @param $lesFraisHF, tableau contenant les valeurs 'S', 'R', ou 'V' pour une cl� 'idFrais'
 */
        public function miseAjourFraisHF($lesFraisHF){
            $lesCles = array_keys($lesFraisHF);
            $i = 0;
            foreach ($lesFraisHF as $unFraisHF){
                $unIdFrais = $lesCles[$i];
                $i++;
                $req = "select lignefraishorsforfait.mois as mois,lignefraishorsforfait.idVisiteur as idVisiteur, lignefraishorsforfait.montant as montant from lignefraishorsforfait 
                    where lignefraishorsforfait.id ='$unIdFrais'";	
                $res = PdoGsb::$monPdo->query($req); 
                $laLigne = $res->fetch();
                $idVisiteur = $laLigne['idVisiteur'];
                $mois = $laLigne['mois']; 
                
                if ($unFraisHF == 'S'){
                    $this->supprimerFraisHorsForfait($unIdFrais);
                } else {
                    if ($unFraisHF == 'R'){
                        $nouveau = getMoisSuivant($mois);
                        $raq = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
                                values('$idVisiteur','$nouveau',0,0,now(),'CR')";
                        PdoGsb::$monPdo->exec($raq);
                        $req = "update lignefraishorsforfait set lignefraishorsforfait.mois = '$nouveau'
			where lignefraishorsforfait.id ='$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
                        }
                }
                
            }
        }
}
?>