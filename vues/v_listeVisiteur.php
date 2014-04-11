<div id="contenu"> 
    <form method="post" action="index.php?uc=validerFrais&action=validerFicheFrais">
		<fieldset>
			<legend>Validation des fiches de frais</legend>
			<p>
				<label for="Visiteur">Visiteur</label>
				<select name="idVisiteur">
				<?php
					foreach($visiteur as $v)
					{
						echo "<option value=".$v['id'].">";
						echo $v['nom']." ".$v['prenom'];
						echo "</option>";
					}
				?>
				</select>
			</p>
			<p>
				<label for="mois">Mois</label>
				<select name="lstMois">
				<?php 
					echo "<option value=".$numAnnee."".$numeroMois.">";			
					echo $nomMois." ".$numAnnee;
					echo "</option>";
				?>
				</select>
			</p>
                        <p>
			<input id="ajouter" type="submit" value="Valider" size="20" />
			<input id="effacer" type="reset" value="Effacer" size="20" />
		</p>
		</fieldset>
		
</form>