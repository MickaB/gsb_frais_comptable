<form method="post" action="index.php?uc=validerFrais&action=validationFicheFrais&type=hforfait">
  	<table class="listeLegere">
  	   <caption>Frais Hors Forfait</caption>
             <tr>
                <th class="date">Date</th>
                <th class="libelle">Libellé</th>
                <th class='montant'>Montant</th>   
				<th class='situation'>Situation</th>
             </tr>
        <?php      
          foreach ( $lesFraisHorsForfait as $unFraisHorsForfait ) 
		  {
			$date = $unFraisHorsForfait['date'];
			$libelle = $unFraisHorsForfait['libelle'];
			$montant = $unFraisHorsForfait['montant'];
		?>
             <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
             </tr>
        <?php 
          }
		?>
    </table>
       
			<input id="envoyer" type="submit" value="Envoyer" size="20" />

</form>