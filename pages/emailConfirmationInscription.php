<?php
/*
  Page     : emailConfirmationInscription.php
  Auteur   : Carluccio Dylan
  Fonction : Page de configuration de l'email pour confirmer son compte
 */

//pour envoyer des emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';


//variable confirmation inscription
$token = openssl_random_pseudo_bytes(16);
$token = bin2hex($token);
$dateActuelle = date("Y-m-d H:i:s");
$idUtilisateur = $dbc->lastInsertId();
$page = 'confirmationInscription.php';
$lien = 'http://localhost/e/pages/' . $page . '?id=' . $idUtilisateur . '&t=' . $token;
if (!isset($_SESSION["User"]['Email'])) {
    $resultat = AfficherUtilisateur($idUtilisateur);
    $prenom = $resultat['Prenom'];
    $email = $resultat['Email'];
}
$message = 'Bonjour ' . $prenom . ',<br/>Bienvenu sur Party Share, tu peut désormais consulter les évènements et y participer. Pour pouvoir en créer un, merci de confirmer ton email avec ce lien: <br/>' . $lien . '.<br/>Merci de votre confiance.<br/>L\'équipe de Party Share';

dbConnect();
//insertion dans la DB
if (InsertionEmailToken($token, $dateActuelle, $idUtilisateur)) {
    $_SESSION['Inscription']['Email'] = $_POST['email'];
    /* on renvoie sur la page de connexion */
    header('Location: connexion.php');
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
    $success = "Inscription réussi, un email de confirmation a été envoyé.";
} catch (Exception $e) {
    $success = FALSE;
}
