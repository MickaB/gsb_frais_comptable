<?php
include("vues/v_sommaire.php");
$action = htmlentities(htmlspecialchars($_REQUEST['action']));
switch($action){
    case 'enCours':{
         // Requete pour infos visiteur VA
        $lesvisiteurs=$pdo->getInfosVisiteurValide();
        $lescles2 = array_keys($lesvisiteurs);
        $utilisateurASelectionner=$lescles2[0];                 

        //Requete pour les mois 
        $lesMois=$pdo->getLesMoisDisponiblesVisiteurValide();                                
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        
        include("vues/v_listeFicheFrais.php");
        break;
    }
    case 'afficherDetailsFiche':{
    
        $idVisiteur = '';
        $idVisiteur = $_REQUEST['idVisiteur'];
        $leMois='';
        $leMois = $_REQUEST['lstMois']; 
        $lesMois= '';
        $lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
        $moisASelectionner ='';
        $moisASelectionner = $leMois;
        // Connaitre le mois pour valider
        $m ='';
        $m = $pdo->obtenirMoisVisiteur();
        $numAnnee ='';
        $numAnnee = substr($m['mois'], 0,4);
        $numMois='';
        $numMois = intval(substr($m['mois'], 4,2));
        $nomMois='';
        $nomMois = obtenirLibelleMois($numMois);
        // on propose tous les Visiteurs
        $visiteur ='';
        $visiteur = $pdo->obtenirReqIdFicheFrais();
        include("vues/v_listeVisiteur.php");
        $NomVisiteur = '';
        $NomVisiteur = $pdo->getNomUtilisateur($idVisiteur);
        $lesFraisHorsForfait ='';
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
        $lesFraisForfait='';
        $lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
        if($lesFraisForfait){
            $lesInfosFicheFrais ='';
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
            $numAnnee =substr( $leMois,0,4);
            $numMois =substr( $leMois,4,2);
            $libEtat='';
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide='';
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $dateModif='';
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModif =  dateAnglaisVersFrancais($dateModif);
            include("vues/v_validationFicheFrais.php");
            include("vues/v_validationFicheFraisHorsF.php");
        }
        else{
            include("vues/v_pasFiche.php");
        }
        break;
    }
    case 'modifEtatFiche':{
        $mois = $_REQUEST['idFiche'];
        $idVisiteur = $_REQUEST['idVisiteur'];
        $req= $pdo->majFicheFraisRembourse($idVisiteur,$mois);
        if($req)
        {
            //la fiche a bien été modifier
        }
        else{
            // erreur lors de l'excecution
        }
    }
}
?>
