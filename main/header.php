<?php
$UrlPage = $_SERVER['PHP_SELF'];
$nomPage = basename($UrlPage);
$page = substr($nomPage, 0, -4);
if ($nomPage === 'index.php') {

    $path = "";
} else {
    $path = "../";
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta name="author" content="Dylan carluccio">
    <meta name="description" content="Résaux social pour soirées,raves ou évènements privées">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page ?> - Party Share</title>
    <link href="https://fonts.googleapis.com/css?family=Marmelad&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo $path ?>css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $path ?>css/index.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.2.3/dist/css/uikit.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>