<form method="post" action="index.php?uc=validerFrais&action=validationFicheFrais&type=hforfait">
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
                        $i++;
		?>
             <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                <td>
                        <select name="action">
                                <option value="V"> Valider </option>
                                <option value="R"> Reporter </option>
                                <option value="S"> Supprimer </option>
                        </select>
                </td>
                <input name="idFrais" type="hidden" value="<?php echo $id ?>"/>
             </tr>
        <?php 
          }
		?>
              <input name="nbTot" type="hidden" value="<?php echo $i ?>"/>
    </table>
    <input id="envoyer" type="submit" value="Envoyer" size="20" />
</form>
