<?php
/*
  Page     : myEvents.php
  Auteur   : Carluccio Dylan
  Fonction : Page pour voir ses évènements crée et rejoint.
 */
session_start();
include('./mysql.php');
include('./fonction.php');
dbConnect();
if (isset($_SESSION['User']['Email'])) {
    $idUtilisateur = $_SESSION['User']['IdUtilisateur'];
}
if (isset($_GET['id'])) {
    $idEvenements = $_GET['id'];
    SupprimerEvenement($idEvenements);
}
if (isset($_GET['idRejoint'])) {
    $idEvenementsRejoint = $_GET['idRejoint'];
    SupprimerRejoint($idUtilisateur, $idEvenementsRejoint);
}

include '../main/header.php';
include '../main/menu.php';
?>
<script type="text/javascript">
    //Confirmation pour delete un évènement
    function DelComment(id) {
        if (confirm("Voulez vous vraiment supprimer cet évènement ?")) {
            window.location = 'myEvents.php?id=' + id
        }
    }

    //Confirmation pour quitter un évènement
    function DelRejointComment(idRejoint) {
        if (confirm("Voulez vous vraiment quitter cet évènement ?")) {
            window.location = 'myEvents.php?idRejoint=' + idRejoint
        }
    }
</script>
</nav>
<div class="coverCadre">
</div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section class="centerFlex">
        <?php
        if (VerifierConnection()) {
            ?>
            <h1>Mes évènements</h1>

            <?php

            $CompteEvenement = CompterEvenements($idUtilisateur);
            $CompterEvenementsRejoint = CompterEvenementsRejoint($idUtilisateur);
            echo '<div id="cadreMyEvents" class="uk-card uk-card-default uk-width-1-2@m"><div class="uk-card-header"> <div class="uk-grid-small uk-flex-middle" uk-grid>   <div class="uk-width-expand"> <h3 class="uk-card-title uk-margin-remove-bottom">';

            echo '</h3>    <p class="uk-text-meta uk-margin-remove-top">';

            echo '</p></div>  </div></div> <div class="uk-card-body">';


            echo "<h3><b>Nombre d'évènements crée: " . $CompteEvenement['Count'], '</b></h3>';
//afficher soirée crée


            echo '<ul uk-accordion  class="k-list uk-list-striped">';
            foreach (AfficherEvenementsCree($idUtilisateur) as $AfficherEvenementsCree) {
                echo '<li><a class="uk-accordion-title" href="#">' . $AfficherEvenementsCree['Titre'] . '</a>
                <div class="uk-accordion-content">';
                echo ' <p>' . $AfficherEvenementsCree['Description'] . '  </p>  <p>
                    <b>Adresse:</b> ' . $AfficherEvenementsCree['Adresse'] . ',
        ' . $AfficherEvenementsCree['CodePostal'] . '
        ' . $AfficherEvenementsCree['Ville'] . '
                </p>
                <p>
                    <b>Date</b>: ' . $AfficherEvenementsCree['Date'] . '
                    de ' . $AfficherEvenementsCree['HeureDebut'] . 'H
                à ' . $AfficherEvenementsCree['HeureFin'] . 'H
                </p>
                <p>
                    <b>Soirée:</b> ' . $AfficherEvenementsCree['Type'] . '
                </p> 
                <span id="boutonEvenements"><a uk-tooltip="Modifier" href="modifierEvents.php?id=' . $AfficherEvenementsCree['IdEvenements'] . '" uk-icon="icon: pencil"></a><a uk-tooltip="Supprimer" href="#" OnClick="DelComment(' . $AfficherEvenementsCree['IdEvenements'] . ')" uk-icon="icon: close"></a></span>        
            
                </div></li>';

            };

            echo '</ul>';

            echo "<h3><b>Nombre d'évènements rejoint: " . $CompterEvenementsRejoint['Count'] . '</b></h3> ';
//afficher evenments rejoint
            echo '<ul uk-accordion  class="k-list uk-list-striped">';
            foreach (AfficherEvenementsRejoint($idUtilisateur) as $AfficherEvenementsRejoint) {
                echo '<li><a class="uk-accordion-title" href="#">' . $AfficherEvenementsRejoint['Titre'] . '</a>
                <div class="uk-accordion-content">';
                echo ' <p>' . $AfficherEvenementsRejoint['Description'] . '  </p>  <p>
                    <b>Adresse:</b> ' . $AfficherEvenementsRejoint['Adresse'] . ',
        ' . $AfficherEvenementsRejoint['CodePostal'] . '
        ' . $AfficherEvenementsRejoint['Ville'] . '
                </p>
                <p>
                    <b>Date</b>: ' . $AfficherEvenementsRejoint['Date'] . '
                    de ' . $AfficherEvenementsRejoint['HeureDebut'] . 'H
                à ' . $AfficherEvenementsRejoint['HeureFin'] . 'H
                </p>
                <p>
                    <b>Soirée:</b> ' . $AfficherEvenementsRejoint['Type'] . '
                </p>';
                echo '<span id="boutonEvenements"></a><a uk-tooltip="Quitter" href="#" OnClick="DelRejointComment(' . $AfficherEvenementsRejoint['IdEvenements'] . ')" uk-icon="icon: close"></a></span>';
                echo '</div></li>';
            };
            echo '</ul>';
            echo '</div><div class="uk-card-footer"> </div></div>';
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
