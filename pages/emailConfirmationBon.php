<?php
/*
  Page     : emailConfirmationBon.php
  Auteur   : Carluccio Dylan
  Fonction : Page de configuration de l'email pour valider la confirmation du compte
 */

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
$date = date("Y-m-d H:i:s");
$message = 'Bonjour ' . $prenom . ',<br/>Ton compte à été confirmé le ' . $date . '<br/> Merci de votre confiance.<br/>L\'équipe de Party Share';


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
    $mail->Subject = 'Confirmation d\'inscription';
    $mail->Body = $message;
    $mail->AltBody = $message;

    $mail->send();
    $success = "Message envoyé, Merci de nous avoir contacté !";
} catch (Exception $e) {
    $success = FALSE;
}