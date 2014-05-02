<?php
include("vues/v_sommaire.php");
$action = $_REQUEST['action'];
switch($action){
	case 'enCours':{
		// Connaitre le mois pour valider
		$m = $pdo->obtenirMoisVisiteur();
		$numAnnee = substr($m['mois'], 0,4);
		$numMois = intval(substr($m['mois'], 4,2));
                $numeroMois = substr($m['mois'], 4,2);
		$nomMois = obtenirLibelleMois($numMois);
		// on propose tous les Visiteurs
		$visiteur = $pdo->obtenirReqIdFicheFrais();
		include("vues/v_listeVisiteur.php");
		break;
	}
	case 'validerFicheFrais':{
		$idVisiteur = $_REQUEST['idVisiteur'];
                $leMois = $_REQUEST['lstMois']; 
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		$moisASelectionner = $leMois;
		// Connaitre le mois pour valider
                $m = $pdo->obtenirMoisVisiteur();
		$numAnnee = substr($m['mois'], 0,4);
		$numMois = intval(substr($m['mois'], 4,2));
                $numeroMois = substr($m['mois'], 4,2);
		$nomMois = obtenirLibelleMois($numMois);
		// on propose tous les Visiteurs
                $visiteur = $pdo->obtenirReqIdFicheFrais();
		include("vues/v_listeVisiteur.php");
                $NomVisiteur = $pdo->getNomUtilisateur($idVisiteur);
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
                $lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
                $test = $pdo->getValidationFicheFrais($idVisiteur,$leMois);
                if($test){
                    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
                    $numAnnee =substr( $leMois,0,4);
                    $numMois =substr( $leMois,4,2);
                    $libEtat = $lesInfosFicheFrais['libEtat'];
                    $montantValide = $lesInfosFicheFrais['montantValide'];
                    $dateModif =  $lesInfosFicheFrais['dateModif'];
                    $dateModif =  dateAnglaisVersFrancais($dateModif);
                    include("vues/v_validationFicheFrais.php");
                    include("vues/v_validationFicheFraisHorsF.php");
                    include("vues/v_validationFinale.php");
                }
                else{
                    ajouterErreur("Pas de fiche de frais pour ce visiteur de ce mois ou fiche de frais déjà validée");
                    include("vues/v_erreurs.php");
                }
		break;
	}
        case 'validationFicheFrais':{
            $idVisiteur = $_POST['id'];
            $mois = $_POST['mois'];
            $quantite1 = $_POST['ETP'];
            $quantite2 = $_POST['KM'];
            $quantite3 = $_POST['NUI'];
            $quantite4 = $_POST['REP'];
            $majFraisForfaitEtape = $pdo->majFraisForfaitValide($idVisiteur, $mois, $quantite1, 'ETP');
            $majFraisForfaitKm = $pdo->majFraisForfaitValide($idVisiteur, $mois, $quantite2, 'KM');
            $majFraisForfaitNuit = $pdo->majFraisForfaitValide($idVisiteur, $mois, $quantite3, 'NUI');
            $majFraisForfaitRepas = $pdo->majFraisForfaitValide($idVisiteur, $mois, $quantite4, 'REP');
            if($majFraisForfaitEtape=='ok' && $majFraisForfaitKm =='ok' && $majFraisForfaitNuit=='ok'  && $majFraisForfaitRepas=='ok'  ){
                echo"<script>alert('La fiche de frais a été modifié avec succès !');
                window.location='index.php?uc=validerFrais&action=validerFicheFrais&idVisiteur=".$idVisiteur."&lstMois=".$mois."';</script>";
            }
            else{
                // Connaitre le mois pour valider
                $m = $pdo->obtenirMoisVisiteur();
                $numAnnee = substr($m['mois'], 0,4);
                $numMois = intval(substr($m['mois'], 4,2));
                $numeroMois = substr($m['mois'], 4,2);
                $nomMois = obtenirLibelleMois($numMois);
                // on propose tous les Visiteurs
                $visiteur = $pdo->obtenirReqIdFicheFrais();
                ajouterErreur("Les valeurs des frais doivent être numériques, veuillez recommencer !");
                include("vues/v_listeVisiteur.php");
                include("vues/v_erreurs.php");
            }          
            break;
        }
        case 'validationFicheHors':{
            $idVisiteur = $_REQUEST['id'];
            $mois = $_REQUEST['mois'];
           if (!empty($_REQUEST['lesFraisHF'])){
                 $lesFraisHF = $_REQUEST['lesFraisHF']; 
                 $ok = $pdo->miseAjourFraisHF($lesFraisHF);
                 if($ok){
                     echo"<script>alert('Les frais hors forfait ont été modifié avec succès !');
                window.location='index.php?uc=validerFrais&action=validerFicheFrais&idVisiteur=".$idVisiteur."&lstMois=".$mois."';</script>";
                 }
            } 
            break;
        }
        case 'validationFicheComplete':{
            $levisiteur = $_POST['id'];
            $leMois = $_POST['mois'];
             /*partie qui recupere les valeur des différents frais afin de les calculer pour avoir le montant total*/
            $LesForfaitEtape = $pdo->getValeurForfaitEtape();               
            $LesFraisKilometrique = $pdo->getValeurFraisKilométrique();              
            $LesFraisNuiteHotel = $pdo->getValeurForfaitNuiteHotel();               
            $LesFraisRepasRestaurant = $pdo->getValeurForfaitNuiteRestaurant();               
            $FraisEtapeUtilisateur = $pdo->getValeurFraisEtapeUtilisateur($levisiteur,$leMois);
            $FraisKilometriqueUtilisateur = $pdo->getValeurFraisKilometriqueUtilisateur($levisiteur,$leMois);
            $FraisNuiteHotelUtilisateur = $pdo->getValeurFraisNuiteHotelUtilisateur($levisiteur,$leMois);
            $FraisRepasRestaurantUtilisateur = $pdo->getValeurFraisRepasRestaurantUtilisateur($levisiteur,$leMois); 
            $TotalFraisHorsForfaitUtilisateur = $pdo->getValeurFraisHorsForfaitUtilisateur($levisiteur,$leMois);
            $MontantTotalValide = (($LesForfaitEtape*$FraisEtapeUtilisateur)+($LesFraisKilometrique*$FraisKilometriqueUtilisateur)+($LesFraisNuiteHotel*$FraisNuiteHotelUtilisateur)+($LesFraisRepasRestaurant*$FraisRepasRestaurantUtilisateur)+$TotalFraisHorsForfaitUtilisateur);              
            $info = $pdo->majFicheFraisValide($levisiteur,$leMois,$MontantTotalValide);
            echo"<script>alert('La fiche de frais a été validé avec succès !');
                    window.location='index.php?uc=validerFrais&action=enCours';</script>";    
            break;
        }
}
?>
