<?php
/*
  Page     : membre.php
  Auteur   : Carluccio Dylan
  Fonction : Page de l'utilisateur connecté.
 */
session_start();
include('./mysql.php');
include('./fonction.php');
include('./emailMotDePasse.php');

dbConnect();
if (isset($_SESSION['User']['Email'])) {
    $email = $_SESSION['User']['Email'];
    $idUtilisateur = $_SESSION['User']['IdUtilisateur'];
    $prenom = $_SESSION['User']['Prenom'];
}
include '../main/header.php';
include '../main/menu.php';
?>
<script type="text/javascript">

    //Confirmation pour quitter un évènement
    function ModifPassComment(id) {
        if (confirm("Voulez vous vraiment envoyer une demande de modification du mot de passe par email ?")) {
            window.location = 'membre.php?id=' + id;
        }
    }
</script>
<div class="coverCadre">
</div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section class="centerFlex">
        <?php
        if (VerifierConnection()) {
            ?>
            <h1>Membre</h1>
            <?php
            $Info = AfficheInformation($email);
            echo '<div id="cadreMembre"class="uk-card uk-card-default uk-width-1-2@m"><div class="uk-card-header"> <div class="uk-grid-small uk-flex-middle" uk-grid>   <div class="uk-width-expand"> <h3 class="uk-card-title uk-margin-remove-bottom">';
            echo $Info['Nom'] . ' ' . $Info['Prenom'];
            echo '</h3>';
            echo '</div>  </div></div> <div class="uk-card-body">';
            echo '<b>Nom:</b> ' . $Info['Nom'] . '<br><br>';
            echo '<b>Prenom:</b> ' . $Info['Prenom'] . '<br><br>';
            echo '<b>Date de naissance:</b> ' . $Info['DateNaissance'] . '<br><br>';
            echo '<b>Email:</b> ' . $Info['Email'] . '<br><br>';
            echo '<b>Pays:</b> ' . $Info['Pays'] . '<br><br>';
            if ($Info['Active'] === '1') {
                echo '<b>Compte activé: </b>Oui<br><br>';
            } else {
                echo '<b>Compte activé: </b>Non - <a href="emailConfirmationRenvoyer.php">Renvoyer une demande</a><br><br>';
            }
            echo '</div><div class="uk-card-footer"><div class="modifierMembre"><a name="modifier" href="./modifier.php?IdUser=' . $Info['IdUtilisateur'] . '" class="uk-button uk-button-text">Modifier ses Informations</a>';
            echo '<a name="modifierPassword"  class="uk-button uk-button-text" OnClick="ModifPassComment( ' . $Info['IdUtilisateur'] . ')">Modifier le mot de passe</a></div></div></div>';
        } else {
            /* on renvoie sur la page d'accueil */
            header('Location: ../index.php');
        }
        ?>
    </section>
</div>
<?php
include '../main/footer.php';
?>
</html>
<?php
if (isset($_SESSION['MDP']['mailOk'])) {
    if ($_SESSION['MDP']['mailOk'] === 1) {
        echo "<script>UIkit.notification({message: 'Email envoyé avec succès', status: 'success'});</script>";
        unset($_SESSION['MDP']['mailOk']);
    }
}
if (isset($_SESSION["MDP"]['MotDePasseModifier'])) {
    $textNotificaion = $_SESSION["MDP"]['MotDePasseModifier'];
    echo "<script>UIkit.notification({message: '$textNotificaion', status: 'success'});</script>";
    unset($_SESSION["MDP"]['MotDePasseModifier']);
}
if (isset($_SESSION["Email"]['Confirmation'])) {
    $textNotificaion = $_SESSION["Email"]['Confirmation'];
    echo "<script>UIkit.notification({message: '$textNotificaion', status: 'success'});</script>";
    unset($_SESSION["Email"]['Confirmation']);
}

?>
