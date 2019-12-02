<?php
/*
  Page     : scrollJS.php
  Auteur   : Carluccio Dylan
  Fonction : page avec le script js qui s'execute quand la page est chargée sauf si on clique sur participer à un évènement dans la page évènement
 */
if (!isset($_SESSION['noScroll'])) {

    ?>
    <script>
        goToByScroll('topBody');

        function goToByScroll(id) {
            // Remove 'link' from the ID
            id = id.replace('link', '');
            // Scroll
            $('html,body').animate({
                scrollTop: $('#' + id).offset().top
            }, 'slow');
        }
    </script>
    <?php
} else {
    unset($_SESSION["noScroll"]);
}
?>