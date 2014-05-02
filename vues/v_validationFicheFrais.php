<form method="post" action="index.php?uc=validerFrais&action=validationFicheFrais">
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
                                $id = $unFraisForfait['idfrais'];
		?>
                <td class="qteForfait">
			<input name="<?php echo $id ?>" value="<?php echo $quantite ?>" size="8" maxlength="5" >
		</td>
		 <?php
          }
		?>
		</tr>
            
    </table>
        <input type="hidden"  name="id" value="<?php echo $idVisiteur ?>" />
        <input type="hidden"  name="mois" value="<?php echo $leMois ?>" />
	<input id="valider" type="submit" value="Valider" size="20" /> 
</form>
	
 
