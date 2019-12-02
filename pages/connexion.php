<?php
/*
  Page     : connexion.php
  Auteur   : Carluccio Dylan
  Fonction : Page de connexion.
 */
session_start();
include('mysql.php');
include('fonction.php');
//variable erreur
if (!isset($erreurEmail)) {
    $erreurEmail = "";
}
if (!isset($erreurPassword)) {
    $erreurPassword = "";
}
//les membres connectes ne peuvent pas s'inscrire
if (empty($_SESSION['User']['Email'])) {
    //si le formulaire a bien été envoyé
    if (!empty($_POST)) {
//verification formulaire
        if (empty($_POST["email"])) {
            $erreurEmail = "l'email est vide.";
        } else {
            $email = $_POST['email'];
            //verification du format de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurEmail = "L'email est invalide. (example@mail.ch)";
            }
        }
        if (empty($_POST["password"])) {
            $erreurPassword = "le mot de passe est vide.";
        } else {
            $password = $_POST["password"];
        }
        if ($erreurEmail == '' && $erreurPassword == '') {
            //crée la variable email à partir du champs email du formulaire envoyé
            $email = $_POST['email'];
            //Verifie si le mail existe / si il existe pas renvoie une erreur
            dbConnect();
            $resultat = VerifierEmail($email);
            if (!$resultat) {
                $erreurPassword = "Email ou mot de passe inncorrects.";
            } else {
                //on verifier le mot de passe
                $MotDePasse = $resultat['MotDePasse'];
                if (password_verify($password, $MotDePasse)) {
                    /* on cree les variables de session du membre qui lui serviront pendant sa session */
                    $_SESSION['User'] = $resultat;
                    /* on renvoie sur la page des évènements */
                    header('Location: events.php');
                } else {
                    $erreurPassword = "Email ou mot de passe inncorrects.";
                }
            }
        }
    }
} else {
    /* on renvoie sur la page d'accueil */
    header('Location: ../index.php');
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
        <h1>Connexion</h1>
        <div id="cadreConnexion" class="uk-card uk-card-default uk-width-1-2@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-expand">
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <form action="connexion.php#cadreConnexion" method="post">
                    <div class="uk-margin">
                        <label>Email</label>
                        <input class="uk-input" type="text" name="email"
                               value="<?php if (isset($_SESSION['Inscription']['Email'])) {
                                   echo $_SESSION['Inscription']['Email'];
                               }; ?>">
                        <div class="erreur"><?php echo $erreurEmail ?></div>
                    </div>
                    <div class="uk-margin">
                        <label>Mot de passe</label>
                        <input class="uk-input" type="password" name="password">
                        <div class="erreur"><?php echo $erreurPassword ?></div>
                    </div>
                    <a href="modifierMotDePasseEmail.php">mot de passe oublié ?</a>
            </div>
            <div class="uk-card-footer">
                <button class="uk-button uk-button-default styleButton">Se connecter</button>
            </div>
            </form>
        </div>
    </section>
</div>
<?php
include '../main/footer.php';
?>
</html>
<?php
//notification

if (isset($_SESSION['Inscription']['Email'])) {
$message='Inscription réussie.</br>Un email de confirmation a été envoyé.';
        echo "<script>UIkit.notification({message: '$message', status: 'success'});</script>";
        unset($_SESSION['Inscription']['Email']);
}
if (isset($_SESSION['MDP']['mailOk'])) {
    if ($_SESSION['MDP']['mailOk'] === 1) {
        echo "<script>UIkit.notification({message: 'Email envoyé avec succès', status: 'success'});</script>";
        unset($_SESSION['MDP']['mailOk']);
    }
}
if (isset($_SESSION["MDP"]['MotDePasseModifier'])) {
    $textNotificaion = $_SESSION["MDP"]['MotDePasseModifier'];
    echo "<script>UIkit.notification({message: '$textNotificaion', status: 'success'});</script>";
    if (isset($_SESSION["MDP"]['MotDePasseModifier'])) {
        unset($_SESSION["MDP"]['MotDePasseModifier']);
    }
    if (isset($_SESSION["MDP"]['Prenom'])) {
        unset($_SESSION["MDP"]['Prenom']);
    }
    if (isset($_SESSION["MDP"]['Token'])) {
        unset($_SESSION["MDP"]['Token']);
    }
    if (isset($_SESSION["MDP"]['Id'])) {
        unset($_SESSION["MDP"]['Id']);
    }
    if (isset($_SESSION["MDP"]['Email'])) {
        unset($_SESSION["MDP"]['Email']);
    }

}
?>
