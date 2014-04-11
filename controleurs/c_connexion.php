<?php
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
		$visiteur = $pdo->getInfosUtilisateur($login,$mdp, "visiteur");
	    $comptable = $pdo->getInfosUtilisateur($login, $mdp, "comptable");
		if(!is_array($visiteur) && !is_array($comptable)){
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else{
			if(!empty($visiteur) && !is_array($comptable)){
				$id = isset($visiteur['id']) ? $visiteur['id'] : "";;
				$nom =  isset($visiteur['nom']) ? $visiteur['nom'] : "";
				$prenom = isset($visiteur['prenom']) ? $visiteur['prenom'] : "";
				connecter($id,$nom,$prenom, 'V');
			}
			if(!empty($comptable) && !is_array($visiteur)){
				$id = isset($comptable['id']) ? $comptable['id'] : "";;
				$nom =  isset($comptable['nom']) ? $comptable['nom'] : "";
				$prenom = isset($comptable['prenom']) ? $comptable['prenom'] : "";
				connecter($id,$nom,$prenom, 'C'); 
			}
			include("vues/v_sommaire.php");
		}
		break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>