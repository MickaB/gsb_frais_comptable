<form method="post" action="index.php?uc=validerFrais&action=validationFicheFrais&type=hors">
  	<table class="listeLegere">
  	   <caption>Frais Hors Forfait</caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>   
                <th class='action'>Action</th>
             </tr>
        <?php     $i=0; 
          foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
		  {
			$date = $unFraisHorsForfait['date'];
			$libelle = $unFraisHorsForfait['libelle'];
			$montant = $unFraisHorsForfait['montant'];
                        $id = $unFraisHorsForfait['id'];
		?>
             <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <td>
                        <select name="action[<?php echo $i ?>]">
                                <option value="V"> Valider </option>
                                <option value="R"> Reporter </option>
                                <option value="S"> Supprimer </option>
                        </select>
                </td>
                <input name="idFrais[<?php echo $i ?>]" type="hidden" value="<?php echo $id ?>"/>
             </tr>
        <?php 
          }
                $i++;
		?>
              <input name="nbTot" type="hidden" value="<?php echo $i ?>"/>
              <input type="hidden"  name="id" value="<?php echo $idVisiteur ?>" />
        <input type="hidden"  name="mois" value="<?php echo $leMois ?>" />
    </table>
    <input id="valider" type="submit" value="Valider" size="20" /> 
</form>
<form method="post" action="index.php?uc=validerFrais&action=validationFicheComplete">
    <input type="hidden"  name="id" value="<?php echo $idVisiteur ?>" />
    <input type="hidden"  name="mois" value="<?php echo $leMois ?>" />
    <input id="envoyer" type="submit" value="Valider la fiche de frais" size="20" />
</form>