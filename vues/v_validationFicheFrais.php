<form method="post" action="index.php?uc=validerFrais&action=validationFicheFrais&type=forfait&id=<?php echo $idVisiteur ?>&mois=<?php echo $leMois ?>">
<h3>Fiche de frais du mois <?php echo $nomMois." ".$numAnnee?> de <?php echo $NomVisiteur['nom']." ".$NomVisiteur['prenom'] ?>: 
     </h3>
  	<table class="listeLegere">
  	   <caption>Frais au Forfait</caption>
        <tr>
         <?php
         foreach ( $lesFraisForfait as $unFraisForfait ) 
		 {
			$libelle = $unFraisForfait['libelle'];
		?>	
			<th> <?php echo $libelle?></th>
		 <?php
        }
		?>
		</tr>
        <tr>
        <?php
          foreach (  $lesFraisForfait as $unFraisForfait  ) 
		  {
				$quantite = $unFraisForfait['quantite'];
                                $idFrais =  $unFraisForfait['idfrais'];
		?>
                <td class="qteForfait">
					<input name="lesFrais[<?php echo $idFrais?>]" value="<?php echo $quantite ?>" size="8" maxlength="5" >
				</td>
		 <?php
          }
		?>
		</tr>
            
    </table>
	<input id="valider" type="submit" value="Valider" size="20" /> 
</form>
	
 
