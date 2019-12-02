<?php
/*
  Page     : modifierEvents.php
  Auteur   : Carluccio Dylan
  Fonction : Page pour modifier son évènement.
 */
session_start();
include('./mysql.php');
include('./fonction.php');
//variable erreur
if (!isset($erreurTitre)) {
    $erreurTitre = "";
}
if (!isset($erreurDescription)) {
    $erreurDescription = "";
}
if (!isset($erreurAdresse)) {
    $erreurAdresse = "";
}
if (!isset($erreurCodePostal)) {
    $erreurCodePostal = "";
}
if (!isset($erreurVille)) {
    $erreurVille = "";
}
if (!isset($erreurDate)) {
    $erreurDate = "";
}
if (!isset($erreurHeureD)) {
    $erreurHeureD = "";
}
if (!isset($erreurHeureF)) {
    $erreurHeureF = "";
}


dbConnect();
if (isset($_SESSION['User']['Email'])) {
    $idUtilisateur = $_SESSION['User']['IdUtilisateur'];
}
if (isset($_GET['id'])) {
    $idEvenements = $_GET['id'];
    if (VerifierConnection()) {
        if (!VerifierEvenementCreateur($idUtilisateur, $idEvenements)) {

            header('Location: ../index.php');
        }
    } else { /* on renvoie sur la page d'accueil */
        header('Location: ../index.php');
    }
    $AfficherEvenementModifier = AfficherEvenementModifier($idEvenements);
}


if (isset($_POST['ValiderModification'])) {
//verification du titre
    if (empty($_POST["titre"])) {
        $erreurNom = "le titre est vide.";
    } else {
        $titre = $_POST['titre'];
        if (strlen($titre) < 2) {
            $erreurTitre = "Il faut minimum 2 caractères.";
        }
        if (strlen($titre) > 45) {
            $erreurTitre = "Il faut maximum 45 caractères.";
        }
    }
//verification du description
    if (empty($_POST["description"])) {
        $erreurDescription = "la description est vide.";
    } else {
        $description = $_POST['description'];
        if (strlen($description) < 2) {
            $erreurDescription = "Il faut minimum 2 caractères.";
        }
    }
//verification du adresse
    if (empty($_POST["adresse"])) {
        $erreurAdresse = "la adresse est vide.";
    } else {
        $adresse = $_POST['adresse'];
        if (strlen($adresse) < 2) {
            $erreurAdresse = "Il faut minimum 2 caractères.";
        }
        if (strlen($adresse) > 100) {
            $erreurAdresse = "Il faut maximum 100 caractères.";
        }
    }
//verification du code postal
    if (empty($_POST["codePostal"])) {
        $erreurCodePostal = "la description est vide.";
    } else {
        $codePostal = $_POST['codePostal'];
        if (strlen($codePostal) < 4) {
            $erreurCodePostal = "Il faut minimum 4 caractères.";
        }
        if (strlen($codePostal) > 5) {
            $erreurCodePostal = "Il faut maximum 5 caractères.";
        }
        if (!preg_match("/^[0-9]*$/", $codePostal)) {
            $erreurCodePostal = "Que des chiffres.";
        }
    }
//verification de la ville
    if (empty($_POST["ville"])) {
        $erreurVille = "la ville est vide.";
    } else {
        $ville = $_POST['ville'];
        if (strlen($ville) < 2) {
            $erreurVille = "Il faut minimum 2 caractères.";
        }
        if (strlen($ville) > 45) {
            $erreurVille = "Il faut maximum 45 caractères.";
        }
        if (!preg_match("/^[a-zA-ZáàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ\s-]*$/", $ville)) {
            $erreurVille = "Lettres, espaces et/ou (-).";
        }
    }
//verification de la date
    if (empty($_POST["date"])) {
        $erreurDate = "La date est vide.";
    } else {
        $origDate = $_POST["date"];
        $date = date("d-m-Y", strtotime($origDate));
        $demain = date("d-m-Y", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
        $deuxAn = date("d-m-Y", mktime(0, 0, 0, date("m"), date("d"), date("Y") + 2));
        if (strtotime($date) < strtotime($demain)) {
            $erreurDate = "> ou = au " . $demain;
        }
        if (strtotime($date) > strtotime($deuxAn)) {
            $erreurDate = "< ou = au " . $deuxAn;
        }
    }
//verification de l'heure de début'
    if (empty($_POST["heureD"])) {
        $erreurHeureD = "la heureD est vide.";
    } else {
        $heureD = $_POST['heureD'];

    }
//verification de l'heure de fin'
    if (empty($_POST["heureF"])) {
        $erreurHeureF = "la heure de fin est vide.";
    } else {
        $heureF = $_POST['heureF'];

    }
    if ($erreurTitre == '' && $erreurDescription == '' && $erreurAdresse == '' && $erreurCodePostal == '' && $erreurVille == '' && $erreurDate == '' && $erreurHeureD == '' && $erreurHeureF == '') {

        if (isset($_GET['id'])) {
            $idEvenement = $_GET['id'];

            $EvenementDonnees['Titre'] = trim($_POST['titre']);
            $EvenementDonnees['Description'] = trim($_POST['description']);
            $EvenementDonnees['Adresse'] = trim($_POST['adresse']);
            $EvenementDonnees['CodePostal'] = trim($_POST['codePostal']);
            $EvenementDonnees['Ville'] = trim($_POST['ville']);
            $EvenementDonnees['Date'] = trim($_POST['date']);
            $EvenementDonnees['HeureDebut'] = trim($_POST['heureD']);
            $EvenementDonnees['HeureFin'] = trim($_POST['heureF']);
            $EvenementDonnees['IdEvenements'] = trim($idEvenement);
            ModifierEvenement($EvenementDonnees);

        }
        /* on renvoie sur la page d'accueil */
        header('Location: myEvents.php');
    }
}
include '../main/header.php';
include '../main/menu.php';
?>
<div class="coverCadre">
</div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section class="centerFlex">
        <?php
        if (VerifierConnection()) {
        ?>
        <h1>Mes évènements</h1>
        <div id="cadreModifierEvents" class="uk-card uk-card-default uk-width-1-2@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-expand">
                    </div>
                </div>
            </div>
            <div class="uk-card-body">
                <form enctype="multipart/form-data" action="" method="post">
                    <div class="uk-margin">
                        <label>Titre</label>
                        <input tabindex="1" type="text" class="uk-input"
                               value="<?= $AfficherEvenementModifier['Titre'] ?>" required name="titre"/>
                        <div class="erreur"><?php echo $erreurTitre ?></div>
                    </div>

                    <div class="uk-margin">
                        <label>Description</label>
                        <textarea tabindex="1" class="uk-input" type="text"
                                  name="description"><?= $AfficherEvenementModifier['Description'] ?></textarea>
                        <div class="erreur"><?php echo $erreurDescription ?></div>
                    </div>
                    <div class="uk-column-1-2">
                        <div class="uk-margin">
                            <label>Adresse</label>
                            <input tabindex="1" type="text" class="uk-input"
                                   value="<?= $AfficherEvenementModifier['Adresse'] ?>" required name="adresse"/>
                            <div class="erreur"><?php echo $erreurAdresse ?></div>
                        </div>
                        <div class="uk-margin">
                            <label>Ville</label>
                            <input tabindex="3" type="text" class="uk-input"
                                   value="<?= $AfficherEvenementModifier['Ville'] ?>" required name="ville"/>
                            <div class="erreur"><?php echo $erreurVille ?></div>
                        </div>
                        <div class="uk-margin">
                            <label>Heure de début</label>
                            <input tabindex="4" type="time" class="uk-input"
                                   value="<?= $AfficherEvenementModifier['HeureDebut'] ?>" required name="heureD"/>
                            <div class="erreur"><?php echo $erreurHeureD ?></div>
                        </div>
                        <div class="uk-margin">
                            <label>Code postal</label>
                            <input tabindex="2" type="text" class="uk-input"
                                   value="<?= $AfficherEvenementModifier['CodePostal'] ?>" required name="codePostal"/>
                        </div>


                        <div class="uk-margin">
                            <label>Date</label>
                            <input tabindex="3" type="Date" class="uk-input"
                                   value="<?= $AfficherEvenementModifier['Date'] ?>" required name="date"/>
                            <div class="erreur"><?php echo $erreurDate ?></div>
                        </div>

                        <div class="uk-margin">
                            <label>Heure de fin</label>
                            <input tabindex="4" type="time" class="uk-input"
                                   value="<?= $AfficherEvenementModifier['HeureFin'] ?>" required name="heureF"/>
                            <div class="erreur"><?php echo $erreurHeureF ?></div>
                        </div>
                    </div>
                    <div class="uk-card-footer">
                        <button class="uk-button uk-button-default styleButton " type="submit"
                                name="ValiderModification">Valider
                        </button>
                    </div>
                </form>
            </div>
            <?php
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
