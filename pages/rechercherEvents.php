<?php
/*
Page     : rechercheEvents.php
Auteur   : Carluccio Dylan
Fonction : Page pour rechercher un évènement
*/
session_start();
include('mysql.php');
include('fonction.php');
//les membres pas connectes ne peuvent pas voir les evenements
if (empty($_SESSION['User']['Email'])) {
    /* on renvoie sur la page d'accueil */
    header('Location: ../index.php');
}
if (!isset($erreurRecherche)) {
    $erreurRecherche = "";
}

include '../main/header.php';
include '../main/menu.php';
?>
<div class="coverCadre">
</div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section id="cadreRecherche">
        <h1 class="centerFlex">Rechercher un évènement</h1>
        <form class="centerFlex" method="post" action="#cadreRecherche">
            <div class="uk-margin">
                <div class="uk-search uk-search-default longeurInput">

                    <span uk-search-icon></span>
                    <input class="uk-search-input" name="recherche" type="search" placeholder="titre..."
                           value="<?php if (isset($_POST['recherche'])) {
                               echo $_POST['recherche'];
                           } ?>">
                </div>
                <div class="uk-margin buttonMillieu">
                    <button class="uk-button uk-button-default buttonSpecial">Rechercher</button>
                </div>
            </div>
        </form>
        <?php

        if (!empty($_POST)) {
            $recherche = $_POST['recherche'];
            $i = 0;
            dbConnect();
            $rechercheVide = RechercherEvenements($recherche);
            if (!$rechercheVide) {

                $erreurRecherche = 'Aucun résultat';
            }
            if (empty($recherche)) {
                $erreurRecherche = "Recherche vide";
            }
            if ($erreurRecherche === "") {
                //afficher l'évènements
                foreach (RechercherEvenements($recherche) as $RechercherEvenements) {

                    ?>

                    <article class="centerFlex">
                        <div>
                            <label class="uk-text-meta">crée
                                le <?php echo $RechercherEvenements['DateCreation']; ?></label>
                        </div>
                        <div class="uk-card uk-card-default uk-width-1-2@m">
                            <div class="uk-card-header">
                                <div class="uk-grid-small uk-flex-middle" uk-grid>
                                    <div class="uk-width-expand">
                                        <h3 class="uk-card-title uk-margin-remove-bottom"><?php echo $RechercherEvenements['Titre']; ?></h3>
                                        <p class="uk-text-meta uk-margin-remove-top"><?php echo $RechercherEvenements['Date']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-card-body">
                                <p>
                                    <?php echo $RechercherEvenements['Description']; ?>
                                </p>
                                <p>
                                    Adresse: <?php echo $RechercherEvenements['Adresse']; ?>,
                                    <?php echo $RechercherEvenements['CodePostal']; ?>
                                    <?php echo $RechercherEvenements['Ville']; ?>
                                </p>
                                <p>
                                    Date: <?php echo $RechercherEvenements['Date']; ?>
                                    de <?php echo $RechercherEvenements['HeureDebut']; ?>H
                                    à <?php echo $RechercherEvenements['HeureFin']; ?>H
                                </p>
                                <p>
                                    Soirée: <?php echo $RechercherEvenements['Type']; ?>
                                </p>
                            </div>
                            <div class="uk-card-footer">

                                <a href="events.php#<?php echo $RechercherEvenements['IdEvenements']; ?>">
                                    <button name="Voir" class="uk-button uk-button-text">
                                        Voir
                                    </button>
                                </a>
                                </form>
                            </div>
                        </div>
                    </article>
                    <?php
                    $i++;
                }
            }
        }
        echo '<div class="centerFlex erreur">' . $erreurRecherche . '</div>';
        ?>
    </section>
</div>
<?php
include '../main/footer.php';
?>
</html>
