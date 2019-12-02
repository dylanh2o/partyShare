<?php
/*
  Page     : menu.php
  Auteur   : Carluccio Dylan
  Fonction : menu de mon site
 */
$UrlPage = $_SERVER['PHP_SELF'];
$nomPage = basename($UrlPage);
if ($nomPage === 'index.php') {

    $path = "";
} else {
    $path = "../";
}
?>
<!-- Nav -->
<nav>
    <a href="<?php echo $path ?>index.php"><label class="Logo">Party Share</label></a>
    <input class="MenuBouton" type="checkbox" id="MenuBouton"/>
    <label class="MenuIcone" for="MenuBouton"><span class="NavIcone"></span></label>
    <ul class="Menu">
        <li><a href="<?php echo $path ?>index.php">Accueil</a></li>
        <?php

        /* si le membre est connecte */
        if (VerifierConnection()) {
            echo '<li><a href="' . $path . 'pages/events.php">Évènements</a></li>';
            echo '<li><a href="' . $path . 'pages/myEvents.php">Mes évènements</a></li>';
            echo '<li><a href="' . $path . 'pages/membre.php">' . $_SESSION['User']['Prenom'] . '</a></li> ';
            echo '<li><a href="' . $path . 'pages/deconnexion.php">Déconnexion</a></li>';
        } else {
            echo '<li><a href="' . $path . 'pages/connexion.php">Connexion</a></li>';
            echo '<li><a href="' . $path . 'pages/inscription.php">Inscription</a></li>';
        }

        ?>
    </ul>
</nav>

