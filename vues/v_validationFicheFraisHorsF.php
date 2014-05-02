<form method="post" action="index.php?uc=validerFrais&action=validationFicheHors">
  	<table class="listeLegere">
  <table class="listeLegere">
        <caption>Descriptif des éléments hors forfait 
        </caption>
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th> 
            <th class='action'>Action</th>   
            
        </tr>
<?php
foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
    $id = $unFraisHorsForfait['id'];
    $date = $unFraisHorsForfait['date'];
    $libelle = $unFraisHorsForfait['libelle'];
    $montant = $unFraisHorsForfait['montant'];
    ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>
                 <td><SELECT name="lesFraisHF[<?php echo $id ?>]">
                        <OPTION selected value ='V'>Valider
                        <OPTION value ='S'>Supprimer
                        <OPTION value ='R'>Reporter
                    </SELECT>
                 </td>
            </tr>
    <?php
    
}
?>
    </table>
            <input type="hidden"  name="id" value="<?php echo $idVisiteur ?>" />
        <input type="hidden"  name="mois" value="<?php echo $leMois ?>" />
      <p>
        <input id="ok" type="submit" value="Valider" size="20" />
      </p> 
</form>
