<?php
/*
  Page     : confirmationInscription.php
  Auteur   : Carluccio Dylan
  Fonction : Page pour confirmer l'inscription
 */
session_start();
include('mysql.php');
include('fonction.php');
dbConnect();
if (isset($_GET['id'])) {
    $idUtilisateur = $_GET['id'];
}
if (isset($_GET['t'])) {
    $token = $_GET['t'];
}


if (VerifierConfirmerInscription($idUtilisateur, $token)) {
//si l'utilisateur possede bien le bon token
    $VerifierConfirmerInscription = VerifierConfirmerInscription($idUtilisateur, $token);
    //verifie si date pas dépassée de 1 heure
    $dateToken = $VerifierConfirmerInscription['Date'];
    $dateToken1H = strtotime($dateToken) + 3600;
    $dateActuelle = date("Y-m-d H:i:s");
    if ($dateToken1H > strtotime($dateActuelle)) {
        //ok peut rester sur le formulaire
        if (!isset($_SESSION["User"]['Email'])) {
            $resultat = AfficherUtilisateur($idUtilisateur);
            $_SESSION['User'] = $resultat;
        }
        $UtilisateurDonnees['idUtilisateur'] = $idUtilisateur;
        ConfirmerUtilisateur($UtilisateurDonnees);
        $_SESSION["Email"]['Confirmation'] = "le compte a été confirmé.";
        include('./emailConfirmationBon.php');
        header('Location: membre.php');


    } else {
        //date dépassée';
        /* on renvoie sur la page d'accueil */
        header('Location: ../index.php');
    }
} else {
    //pas le bon token ou utilisateur
    /* on renvoie sur la page d'accueil */
    header('Location: ../index.php');
}

include '../main/header.php';
include '../main/menu.php';
?>
</nav>
<div class="coverCadre">
</div>
<div class="uk-container uk-margin">
    <section class="centerFlex">
        <h1>Confirmer Inscription</h1>

    </section>
</div>
<?php
include '../main/footer.php';
?>
</html>
