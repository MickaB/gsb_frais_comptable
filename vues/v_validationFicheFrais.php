<form method="post" action="index.php?uc=validerFrais&action=validationFicheFrais&type=forfait">
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
		?>
                <td class="qteForfait"><?php echo $quantite?> </td>
		 <?php
          }
		?>
		</tr>
            
    </table>
     <input id="valider" type="submit" value="Valider" size="20" /> 
</form>
	
 
