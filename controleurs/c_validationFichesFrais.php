<?php
include("vues/v_sommaire.php");
$action = htmlentities(htmlspecialchars($_REQUEST['action']));
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
		$nomMois = obtenirLibelleMois($numMois);
		// on propose tous les Visiteurs
                $visiteur = $pdo->obtenirReqIdFicheFrais();
		include("vues/v_listeVisiteur.php");
                $NomVisiteur = $pdo->getNomUtilisateur($idVisiteur);
                $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
                $lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
                if($lesFraisForfait){
                    $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
                    $numAnnee =substr( $leMois,0,4);
                    $numMois =substr( $leMois,4,2);
                    $libEtat = $lesInfosFicheFrais['libEtat'];
                    $montantValide = $lesInfosFicheFrais['montantValide'];
                    $dateModif =  $lesInfosFicheFrais['dateModif'];
                    $dateModif =  dateAnglaisVersFrancais($dateModif);
                    include("vues/v_validationFicheFrais.php");
                    include("vues/v_validationFicheFraisHorsF.php");
                }
                else{
                    ajouterErreur("Pas de fiche de frais pour ce visiteur de ce mois ou fiche de frais déjà validée");
                    include("vues/v_erreurs.php");
                }
		break;
	}
        case 'validationFicheFrais':{
            if('forfait' == $_REQUEST['type']){
                $info = $pdo->majEtatFicheFrais($idVisiteur,$mois,$etat);
            }
            if('hforfait' == $_REQUEST['type']){
                $info = $pdo->majEtatFicheFrais($idVisiteur,$mois,$etat);
            }
            break;
        }
}
?>
