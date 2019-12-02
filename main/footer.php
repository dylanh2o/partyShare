<!-- UIkit JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/js/uikit.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.2.0/js/uikit-icons.min.js"></script>

</body>

<footer>
    Copyright Dylan
</footer>
<?php
$UrlPage = $_SERVER['PHP_SELF'];
$nomPage = basename($UrlPage);
$page = substr($nomPage, 0, -4);
if ($nomPage === 'index.php') {
    $path = "pages/";

} else {
    $path = "";
}
include($path . 'scrollJS.php');
?>
