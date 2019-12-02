<?php
/*
  Page     : deconnexion.php
  Auteur   : Carluccio Dylan
  Fonction : Page pour se déconnecter et vider la session
 */
/* il faut demarrer la session */
session_start();
if (isset($_SESSION['User']['Email'])) //les membres non connectes ne peuvent pas se deconnecter
{

    /* on vire toutes la variables de session */
    $_SESSION = array();
    session_destroy();

    /* on renvoie sur la page de connexion */
    header('Location: connexion.php');
} else {
    /* on renvoie sur la page home */
    header('Location: ../inscription.php');
}
?>