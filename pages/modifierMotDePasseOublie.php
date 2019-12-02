<?php
/*
  Page     : modifierMotDePasse.php
  Auteur   : Carluccio Dylan
  Fonction : Page pour changer son mot de passe par mail
 */
session_start();
include('mysql.php');
include('fonction.php');
dbConnect();
//variable erreur
if (!isset($erreurPassword)) {
    $erreurPassword = "";
}
if (!isset($erreurPassword2)) {
    $erreurPassword2 = "";
}

if (isset($_GET['t'])) {
    $token = $_GET['t'];
}

if (isset($_SESSION["MDP"]["Email"])) {
    $email = $_SESSION["MDP"]["Email"];
}
if (VerifierTokenEmail($email, $token)) {
    //si l'utilisateur possede bien le bon token
    $utilisateurToken = VerifierTokenEmail($email, $token);
    $idUtilisateur = $utilisateurToken['IdUtilisateur'];
    //verifie si date pas dépassée de 1 heure
    $dateToken = $utilisateurToken['Date'];
    $dateToken1H = strtotime($dateToken) + 1800;
    $dateActuelle = date("Y-m-d H:i:s");
    if ($dateToken1H > strtotime($dateActuelle)) {
        //ok peut rester sur le formulaire
    } else {
        //date dépassée
        /* on renvoie sur la page d'accueil */
        header('Location: ../index.php');
    }
} else {
    //pas le bon token ou utilisateur
    /* on renvoie sur la page d'accueil */
    header('Location: ../index.php');
}
//si le formulaire a bien été envoyé
if (!empty($_POST)) {
//verification du mot de passe
    if (empty($_POST["password"])) {
        $erreurPassword = "le mot de passe est vide.";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 4) {
            $erreurPassword = "Il faut minimum 4 caractères.";
        }
    }
//verification du deuxième mot de passe
    if (empty($_POST["password2"])) {
        $erreurPassword2 = "le mot de passe est vide.";
    } else {
        $password2 = $_POST["password2"];
        if (strlen($password2) < 4) {
            $erreurPassword2 = "Il faut minimum 4 caractères.";
        }
    }
//verification si les mot de passe sont identiques
    if ($_POST["password"] !== $_POST["password2"]) {
        $erreurPassword2 = "Les mots de passes ne correspondent pas";
    }
    if ($erreurPassword == '' && $erreurPassword2 == '') {
        /* on crypte le mot de passe */
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $EvenementDonnees['password'] = $password;
        $EvenementDonnees['idUtilisateur'] = $idUtilisateur;
        ModifierMotDePasse($EvenementDonnees);
        $_SESSION["MDP"]['MotDePasseModifier'] = 'Mot de passe modifié avec succès';
        include('./emailMotDePasseBon.php');
        header('Location: connexion.php');
    }
}
include '../main/header.php';
include '../main/menu.php';
?>
</nav>
<div class="coverCadre">
</div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section class="centerFlex">
        <h1>Modifier mot de passe</h1>
        <div id="cadreMDP" class="uk-card uk-card-default uk-width-1-2@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-expand">
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <form action="#cadreMDP" method="post">
                    <div class="uk-margin">
                        <label>Nouveau mot de passe
                            <input class="uk-input" type="password" name="password">
                            <div class="erreur"><?php echo $erreurPassword ?></div>
                    </div>
                    <div class="uk-margin">
                        <label>Répetez le mot de passe</label>
                        <input class="uk-input" type="password" name="password2">
                        <div class="erreur"><?php echo $erreurPassword2 ?></div>
                    </div>
            </div>
            <div class="uk-card-footer">
                <button class="uk-button uk-button-default styleButton">Modifier</button>
            </div>
            </form>
        </div>
    </section>
</div>
<?php
include '../main/footer.php';
?>
</html>
