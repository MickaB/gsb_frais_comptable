 <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
    
        <h2></h2>
    
      </div>  
        <ul id="menuList">
<?php 
  if(isset($_SESSION['utilisateur']) && $_SESSION['utilisateur']== 'V'){?>

			<li >
				  Visiteur :<br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom'];  ?>
			</li>
           <li class="smenu">
              <a href="index.php?uc=gererFrais&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=etatFrais&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
           </li>
         
<?php
}
if(isset($_SESSION['utilisateur']) && $_SESSION['utilisateur'] == 'C'){?>
  
			<li >
				  Comptable :<br>
				<?php echo $_SESSION['prenom']."  ".$_SESSION['nom'];  ?>
			</li>
           <li class="smenu">
              <a href="index.php?uc=validerFrais&action=enCours" title="Valider frais du mois">Valider frais du mois</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=suivreFrais&action=enCours" title="Suivre un paiement">Suivre un paiement</a>
           </li>
<?php
}
?>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
		</ul>
    </div>
    