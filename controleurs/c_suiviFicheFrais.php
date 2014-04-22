<?php
include("vues/v_sommaire.php");

if(isset($_GET['action2']) && $_GET['action2']=="Payer")
    {
        $pdo->majEtatFicheFrais($_POST['id'],$_POST['mois'],"RB");
        ?>
        <script>alert('La fiche de frais a été payée avec succès !');
            window.location='index.php?uc=suivreFrais&action=enCours';</script>
           <?php
    }

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
    
       $leMois = $_REQUEST['lstMois']; 
        $levisiteur = $_REQUEST['idVisiteur'];                
        // Requete pour infos visiteur VA
        $lesvisiteurs=$pdo->getInfosVisiteurValide();
        $utilisateurASelectionner=$_REQUEST['idVisiteur']; 

        //Requete pour les mois 
        $lesMois=$pdo->getLesMoisDisponiblesVisiteurValide();                                
        // Afin de sélectionner par défaut le dernier mois dans la zone de liste
        // on demande toutes les clés, et on prend la première,
        // les mois étant triés décroissants
        $lesCles = array_keys($lesMois);
        $moisASelectionner = $lesCles[0];
        include("vues/v_listeFicheFrais.php");
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($levisiteur,$leMois);
        $lesFraisForfait= $pdo->getLesFraisForfait($levisiteur,$leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($levisiteur,$leMois);
        $numAnnee =substr( $leMois,0,4);
        $numMois =substr( $leMois,4,2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif =  $lesInfosFicheFrais['dateModif'];
        $dateModif =  dateAnglaisVersFrancais($dateModif);
        include("vues/v_montantValider.php");
        break;
    }
}
?>
