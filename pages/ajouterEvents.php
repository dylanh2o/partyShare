<?php
/*
  Page     : ajouterEvents.php
  Auteur   : Carluccio Dylan
  Fonction : Page pour l'insertion d'un évènement.
 */
session_start();
include('mysql.php');
include('fonction.php');
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


if (!empty($_SESSION['User']['Email'])) { //les membres pas connectes ne peuvent ajouter un evenement
    if (!empty($_POST)) {//si le formulaire est bien ete envoyé


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

            dbConnect();
            //variable non déclaré
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $adresse = $_POST['adresse'];
            $codePostal = $_POST['codePostal'];
            $ville = $_POST['ville'];
            $date = $_POST['date'];
            $heureD = $_POST['heureD'];
            $heureF = $_POST['heureF'];
            $type = 'Public';
            $dateCreation = date("Y/m/d");
            $idUtilisateur = $_SESSION['User']['IdUtilisateur'];

            if (InscriptionEvents($titre, $description, $adresse, $codePostal, $ville, $date, $heureD, $heureF, $type, $dateCreation, $idUtilisateur)) {
                /* on renvoie sur la page de connexion */
                header('Location: myEvents.php#cadreMyEvents');
            } else {
                echo "Une erreur s'est produite dans l'insertion des données ";
            }
        } else {
            //pour bien afficher les erreurs
            if ($erreurAdresse === "") {
                $erreurAdresse = "&nbsp";
            }
            if ($erreurCodePostal === "") {
                $erreurCodePostal = "&nbsp";
            }
            if ($erreurVille === "") {
                $erreurVille = "&nbsp";
            }
            if ($erreurDate === "") {
                $erreurDate = "&nbsp";
            }
            if ($erreurHeureD === "") {
                $erreurHeureD = "&nbsp";
            }
            if ($erreurHeureF === "") {
                $erreurHeureF = "&nbsp";
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
<div class="coverCadre"></div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section>
        <h1 class="centerFlex">Ajouter un évènement</h1>

        <article class="centerFlex">
            <div id="cadreAjouterEvenement" class="uk-card uk-card-default uk-width-1-2@m">
                <div class="uk-card-header">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-expand">
                        </div>
                    </div>
                </div>
                <div class="uk-card-body">
                    <form method="post" action="ajouterEvents.php#cadreAjouterEvenement">


                        <div class="uk-margin">
                            <label>Titre</label>
                            <input tabindex="1" class="uk-input" type="text" required name="titre"
                                   value="<?php if (isset($_POST['titre'])) {
                                       echo $_POST['titre'];
                                   } ?>"/>
                            <div class="erreur"><?php echo $erreurTitre ?></div>
                        </div>

                        <div class="uk-margin">
                            <label>Description</label>
                            <textarea tabindex="1" class="uk-input" type="text" required
                                      name="description"><?php if (isset($_POST['description'])) {
                                    echo $_POST['description'];
                                } ?></textarea>
                            <div class="erreur"><?php echo $erreurDescription ?></div>
                        </div>
                        <div class="uk-column-1-2">

                            <div class="uk-margin">
                                <label>Adresse</label>
                                <input tabindex="1" type="text" class="uk-input" required name="adresse"
                                       value="<?php if (isset($_POST['adresse'])) {
                                           echo $_POST['adresse'];
                                       } ?>"/>
                                <div class="erreur"><?php echo $erreurAdresse ?></div>
                            </div>

                            <div class="uk-margin">
                                <label>Ville</label>
                                <input tabindex="3" type="text" class="uk-input" required name="ville"
                                       value="<?php if (isset($_POST['ville'])) {
                                           echo $_POST['ville'];
                                       } ?>"/>
                                <div class="erreur"><?php echo $erreurVille ?></div>
                            </div>
                            <div class="uk-margin">
                                <label>Heure de début</label>
                                <input tabindex="4" type="time" class="uk-input" required name="heureD"
                                       value="<?php if (isset($_POST['heureD'])) {
                                           echo $_POST['heureD'];
                                       } ?>"/>
                                <div class="erreur"><?php echo $erreurHeureD ?></div>
                            </div>
                            <div class="uk-margin">
                                <label>Code postal</label>
                                <input tabindex="2" type="text" class="uk-input" required name="codePostal"
                                       value="<?php if (isset($_POST['codePostal'])) {
                                           echo $_POST['codePostal'];
                                       } ?>"/>
                                <div class="erreur"><?php echo $erreurCodePostal ?></div>
                            </div>


                            <div class="uk-margin">
                                <label>Date</label>
                                <input tabindex="3" type="Date" class="uk-input" required name="date"
                                       value="<?php if (isset($_POST['date'])) {
                                           echo $_POST['date'];
                                       } ?>"/>
                                <div class="erreur"><?php echo $erreurDate ?></div>
                            </div>

                            <div class="uk-margin">
                                <label>Heure de fin</label>
                                <input tabindex="4" type="time" class="uk-input" required name="heureF"
                                       value="<?php if (isset($_POST['heureF'])) {
                                           echo $_POST['heureF'];
                                       } ?>"/>
                                <div class="erreur"><?php echo $erreurHeureF ?></div>
                            </div>

                        </div>
                        <div class="uk-card-footer">
                            <button class="uk-button uk-button-text ">Ajouter</button>
                        </div>
                    </form>
                </div>
        </article>

        <br>
    </section>
</div>
<?php
include '../main/footer.php';
?>
</html>
