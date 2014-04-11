<div id="contenu"> 
<form method="post" action="index.php?uc=suivreFrais&action=afficherDetailsFiche">
     
            <p>

        <label for="lstVisiteur" accesskey="n">Visiteur : </label>
        <select id="lstVisiteur" name="lstVisiteur">
            <?php
			foreach ($lesvisiteurs as $unVisiteur)
			{
                                
				$VisiteurID = $unVisiteur['id'];
                                $VisiteurNom = $unVisiteur['nom']; 
                                
                                $VisiteurPrenom = $unVisiteur['prenom']; 
                                
				if($VisiteurID == $utilisateurASelectionner){
				?>
				<option selected value="<?php echo $VisiteurID ?>"><?php echo   $VisiteurPrenom." ".$VisiteurNom ?> </option>
                                <?php
				}
				else{ ?>
				<option value="<?php echo $VisiteurID ?>"><?php echo   $VisiteurPrenom." ".$VisiteurNom ?> </option>
                                <?php

				}

			}
           
		   ?>    
            
        </select>
        
      </p>         
        

            <p>
	 
        <label for="lstMois" accesskey="n">Mois : </label>
        <select id="lstMois" name="lstMois">
            <?php
			foreach ($lesMois as $unMois)
			{
                                $mois = $unMois['mois'];
				$numAnnee =  $unMois['numAnnee'];
				$numMois =  $unMois['numMois'];
				if($mois == $moisASelectionner){
				?>
				<option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}

			}
           
		   ?>    
            
        </select>
      
            </p>
      
      
    
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
        <input id="annuler" type="reset" value="Effacer" size="20" />
      </p> 
        
  
</form>