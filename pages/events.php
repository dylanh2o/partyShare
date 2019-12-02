<?php
/*
Page     : events.php
Auteur   : Carluccio Dylan
Fonction : Page pour voir les évènements des utilisateurs et y participer.
*/
session_start();
include('mysql.php');
include('fonction.php');
if (empty($_SESSION['User']['Email'])) { //les membres pas connectes ne peuvent pas voir les evenements
    /* on renvoie sur la page d'accueil */
    header('Location: ../index.php');
}
dbConnect();
if (!isset($erreurEvents)) {
    $erreurEvents = "";
}
$idUtilisateur = $_SESSION['User']['IdUtilisateur'];
if (isset($_POST['creerEvent'])) {

    $AfficherUtilisateur = AfficherUtilisateur($idUtilisateur);
    $active = $AfficherUtilisateur['Active'];
    if ($active === '0') {
        $_SESSION['Email']['Active'] = '0';
    } else {
        header('Location: ajouterEvents.php');
    }
}
//si un utilisateur veut participer à un évènement
if (isset($_POST['participer'])) {
    //verifie si c'est le createur de l'évènement.
    if (!VerifierEvenementCreateur($_SESSION['User']['IdUtilisateur'], $_POST['idEvenementsParticiper'])) {
        $Createur = 1;
    } else {
        $Createur = 0;
        $inscrit = 0;
        $erreurRejoindre = "Impossible de rejoindre son évènement.";
    }
    //verifie si l'utilisateur à déjà rejoint l'évènement.
    if (!VerifierEvenementsRejoint($_SESSION['User']['IdUtilisateur'], $_POST['idEvenementsParticiper'])) {
        $Rejoint = 1;
        $_SESSION['noScroll'] = '0';
    } else {
        $idEvenements = $_POST['idEvenementsParticiper'];
        SupprimerRejoint($idUtilisateur, $idEvenements);
        $Rejoint = 0;
        $inscrit = 0;
        $_SESSION['noScroll'] = '0';
        $erreurRejoindre = "Evènement quitté.";
    }
//si tout bon on envoie la requete
    if ($Createur === 1 && $Rejoint === 1) {
        $idEvenements = $_POST['idEvenementsParticiper'];
        if (RejoindreEvenement($idUtilisateur, $idEvenements)) {
            $inscrit = 1;

        }
    }
}

//Remplie le tableau des evenements que l'utilisateur courant à rejoint.
foreach (AfficherEvenementsRejoint($_SESSION['User']['IdUtilisateur']) as $AfficherEvenementsRejoint) {

    $tableauEvenementsRejoint[] = $AfficherEvenementsRejoint['IdEvenements'];
};
//Remplie le tableau des evenements que l'utilisateur courant à crée.
foreach (AfficherEvenementsCree($_SESSION['User']['IdUtilisateur']) as $AfficherEvenementsCree) {
    $tableauEvenementsCree[] = $AfficherEvenementsCree['IdEvenements'];
}


include '../main/header.php';
include '../main/menu.php';
?>
<div class="coverCadre"></div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section>
        <h1 class="centerFlex">Evénements</h1>
        <div class="flexEvents">
            <div>
                <a href="rechercherEvents.php">
                    <button class="uk-button uk-button-default buttonSpecial">Rechercher un évènement</button>
                </a>
            </div>
            <div>
                <form method="post" action="">

                    <button name="creerEvent" class="uk-button uk-button-default buttonSpecial">Créer un évènement
                    </button>

                </form>
            </div>
        </div>

        <?php
        $i = 0;
        //afficher l'évènements
        if (AfficheEvenements()) {
            foreach (AfficheEvenements() as $evenements) {

                //afficher differement le bouton participer
                //déja rejoint - si l'utilisateur à deja particper
                //modifier - si c'est l'évènement de l'utilisateur
                // ou simplement participer
                if (!empty($tableauEvenementsCree)) {
                    foreach ($tableauEvenementsCree as $value) {
                        if ($evenements['IdEvenements'] === $value) {
                            $buttonText = "Modifier";
                            $classBouton = "";
                            $pageFormulaire = "modifierEvents.php?id=";
                            break;
                        } else {
                            if (!empty($tableauEvenementsRejoint)) {
                                foreach ($tableauEvenementsRejoint as $value) {
                                    if ($evenements['IdEvenements'] === $value) {
                                        $classBouton = "boutonRejoint";
                                        $buttonText = "Dèjà rejoint";
                                        $pageFormulaire = "events.php#";
                                        break;
                                    } else {
                                        $classBouton = "";
                                        $buttonText = "Participer";
                                        $pageFormulaire = "events.php#";
                                    }
                                }
                            }else {
                                $classBouton = "";
                                $buttonText = "Participer";
                                $pageFormulaire = "events.php#";
                            }
                        }
                    }
                } else {
                    if (!empty($tableauEvenementsRejoint)) {
                        foreach ($tableauEvenementsRejoint as $value) {
                            if ($evenements['IdEvenements'] === $value) {
                                $classBouton = "boutonRejoint";
                                $buttonText = "Dèjà rejoint";
                                $pageFormulaire = "events.php#";
                                break;
                            } else {
                                $classBouton = "";
                                $buttonText = "Participer";
                                $pageFormulaire = "events.php#";
                            }
                        }
                    } else {
                        $classBouton = "";
                        $buttonText = "Participer";
                        $pageFormulaire = "events.php#";
                    }
                }


                ?>
                <div class="topCadre" id="<?php echo $evenements['IdEvenements']; ?>">
                </div>
                <article class="centerFlex">
                    <div>
                        <label class="uk-text-meta">crée le <?php echo $evenements['DateCreation']; ?></label>
                        <label class="uk-text-meta">par <?php echo $evenements['Prenom']; ?></label>
                    </div>
                    <div class="uk-card uk-card-default uk-width-1-2@m">
                        <div class="uk-card-header">
                            <div class="uk-grid-small uk-flex-middle" uk-grid>
                                <div class="uk-width-expand">
                                    <h3 class="uk-card-title uk-margin-remove-bottom"><?php echo $evenements['Titre']; ?></h3>
                                    <p class="uk-text-meta uk-margin-remove-top"><?php echo $evenements['Date']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="uk-card-body">
                            <p>
                                <?php echo nl2br(htmlentities($evenements['Description'])); ?>
                            </p>
                            <p>
                                Adresse: <?php echo $evenements['Adresse']; ?>,
                                <?php echo $evenements['CodePostal']; ?>
                                <?php echo $evenements['Ville']; ?>
                            </p>
                            <p>
                                Date: <?php echo $evenements['Date']; ?>
                                de <?php echo $evenements['HeureDebut']; ?>H
                                à <?php echo $evenements['HeureFin']; ?>H
                            </p>
                            <p>
                                Soirée: <?php echo $evenements['Type']; ?>
                            </p>
                        </div>
                        <div class="uk-card-footer">
                            <form method="post" action="<?php echo $pageFormulaire . $evenements['IdEvenements']; ?>">
                                <input type="hidden" name="idEvenementsParticiper"
                                       value="<?php echo $evenements['IdEvenements']; ?>"/>
                                <button name="participer"
                                        class="uk-button uk-button-default styleButton <?php echo $classBouton ?>">
                                    <?php echo $buttonText; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
                <?php
                $i++;
            }
        } else {
            $erreurEvents = 'Aucun évènements à venir.';
        }
        echo '<div class="centerFlex erreur">' . $erreurEvents . '</div></br></br></br>';
        ?>
    </section>
</div>
<?php
include '../main/footer.php';
?>
</html>
<?php
if (isset($inscrit)) {
    if ($inscrit === 1) {
        echo "<script>UIkit.notification({message: 'Evènement rejoint.', status: 'success'});</script>";
    }
    if ($inscrit === 0) {
        echo "<script>UIkit.notification({message: '" . $erreurRejoindre . "', status: 'Danger'});</script>";
    }
}
if (isset($_SESSION["Email"]['Active'])) {
    $textNotificaion = 'Confirmer votre email pour créer un évènement.</br>(Rendez vous <a href="membre.php#cadreMembre">ici</a>.)';
    echo "<script>UIkit.notification({message: '$textNotificaion', status: 'Danger'});</script>";
    unset($_SESSION["Email"]['Active']);
}

?>

