/*
Page     : index.php
Auteur   : Carluccio Dylan
Fonction : Page d'accueil du site
*/
<?php
session_start();
include('pages/mysql.php');
include('pages/fonction.php');
include 'main/header.php';
include 'main/menu.php';
dbConnect();
$CompteUtilisateursEtEvents = CompteUtilisateursEtEvents();
$nombreUtilisateurs = $CompteUtilisateursEtEvents['0']['Compte'];
$nombreEvenements = $CompteUtilisateursEtEvents['1']['Compte'];
?>
</nav>
<div class="coverCadre"></div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section class="flexHome">
        <article>
            <p>Bienvenu sur Party Share,</p>
            <p>Ici la communauté partage plusieurs raves, soirées ou un autres événements privés.</p>
            <p>Crée vite ton compte pour accéder à ces événements créé par notre communauté et partager les tiens !</p>
            <ul>
                <li>Crée ton compte avec tes informations</li>
                <li>Partage tes évènements ou rejoint celui qui te plaît.</li>
            </ul>
            Inscris toi vite <a href="pages/inscription.php">ici</a> !
        </article>
        <br>
        <article class="test">
            <?php

            echo 'Il y a ' . $nombreUtilisateurs . ' utilisateurs</br>et ' . $nombreEvenements . ' évènements sur le site'

            ?>
        </article>
    </section>
</div>
<?php
include 'main/footer.php';
?>
</html>
