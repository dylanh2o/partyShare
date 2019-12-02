<?php
/*
  Page     : emailConfirmationRenvoyer.php
  Auteur   : Carluccio Dylan
  Fonction : Page de configuration de l'email pour renvoyer la confrimation du compte
 */

session_start();
include('mysql.php');
include('fonction.php');

//pour envoyer des emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if (isset($_SESSION['User']['Email'])) {
    $email = $_SESSION['User']['Email'];
    $idUtilisateur = $_SESSION['User']['IdUtilisateur'];
    $prenom = $_SESSION['User']['Prenom'];
}
//variable confirmation inscription
$token = openssl_random_pseudo_bytes(16);
$token = bin2hex($token);
$dateActuelle = date("Y-m-d H:i:s");
$page = 'confirmationInscription.php';
$lien = 'http://localhost/e/pages/' . $page . '?id=' . $idUtilisateur . '&t=' . $token;
dbConnect();
$message = 'Bonjour ' . $prenom . ',<br/>Nous avons reçu une demande de confirmation, si ce n\'est pas vous ignoré cet email.<br/>Sinon merci de confirmer votre email avec le lien suivant dans l\'heure à venir: <br/>' . $lien . '.<br/>Merci de votre confiance.<br/>L\'équipe de Party Share';

dbConnect();
//insertion dans la DB
if (InsertionEmailToken($token, $dateActuelle, $idUtilisateur)) {
    /* on renvoie sur la page de connexion */
    header('Location: membre.php#cadreMembre');
} else {
    $resultatsText = "Une erreur s'est produite dans l'insertion des données de confrimation de l'email";
}

// Envoye de email
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->SMTPDebug = 0;                                       // Enable verbose debug output
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                                   // Enable SMTP authentication
    $mail->Username = 'sae.email555@gmail.com';                     // SMTP username
    $mail->Password = 'email555';                               // SMTP password
    $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('sae.email555@gmail.com', 'Party Share');
    $mail->addAddress($email, $prenom);     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Inscription et confirmation';
    $mail->Body = $message;
    $mail->AltBody = $message;

    $mail->send();
    $success = "Message envoyé, Merci de nous avoir contacté !";
    $_SESSION['MDP']['mailOk'] = 1;
} catch (Exception $e) {
    $success = FALSE;
}
