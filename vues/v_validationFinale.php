<form method="post" action="index.php?uc=validerFrais&action=validationFicheComplete">
    <input type="hidden"  name="id" value="<?php echo $idVisiteur ?>" />
    <input type="hidden"  name="mois" value="<?php echo $leMois ?>" />
    <input id="envoyer" type="submit" value="Valider la fiche de frais" size="20" />
</form>