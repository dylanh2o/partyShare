<?php
/*
  Page     : modifierMotDePasseEmail.php
  Auteur   : Carluccio Dylan
  Fonction : Page pour changer son mot de passe par mail
 */
session_start();
include('mysql.php');
include('fonction.php');
dbConnect();
//variable erreur
if (!isset($erreurEmail)) {
    $erreurEmail = "";
}
//si le formulaire a bien été envoyé
if (!empty($_POST)) {
//verification de l'email
    if (empty($_POST["email"])) {
        $erreurEmail = "l'email est vide.";
    } else {
        $email = $_POST['email'];
        //verification du format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurEmail = "L'email est invalide. (example@mail.ch)";
        }
    }
    if ($erreurEmail == '') {
        $resultat = VerifierEmail($email);
        if (!$resultat) {
            $erreurEmail = "Email incconnu.";
        } else {
            $token = openssl_random_pseudo_bytes(16);
            $token = bin2hex($token);
            $prenom = $resultat['Prenom'];
            $id = $resultat['IdUtilisateur'];
            $_SESSION["MDP"]['Prenom'] = $prenom;
            $_SESSION["MDP"]['Token'] = $token;
            $_SESSION["MDP"]['Id'] = $id;
            $_SESSION["MDP"]['Email'] = $email;
            include('emailMotDePasseOublie.php');
            header('Location: connexion.php');
        }
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
        <div id="cadreMDPEmail" class="uk-card uk-card-default uk-width-1-2@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-expand">
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <form action="#cadreMDPEmail" method="post">
                    <div class="uk-margin">
                        <label>Email</label>
                        <input class="uk-input" type="text" name="email">
                        <div class="erreur"><?php echo $erreurEmail ?></div>
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
