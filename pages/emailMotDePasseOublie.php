<?php
/*
  Page     : emailMotDePasseOublie.php
  Auteur   : Carluccio Dylan
  Fonction : Page de configuration de l'email pour changer son password si on la oublié
 */

//pour envoyer des emails
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

if (isset($_SESSION["MDP"]["Prenom"])) {
    $prenom = $_SESSION["MDP"]["Prenom"];
    $token = $_SESSION["MDP"]["Token"];
    $idUtilisateur = $_SESSION["MDP"]["Id"];
    $email = $_SESSION["MDP"]["Email"];
    $page = 'modifierMotDePasseOublie.php';
    $date = date("Y-m-d H:i:s");
    $lien = 'http://localhost/e/pages/' . $page . '?t=' . $token;
    $message = 'Bonjour ' . $prenom . ',<br/>Nous avons reçu de votre part le ' . $date . ' une demande pour changer votre mot de passe.<br/> Si ce n\'est pas vous qui avez fait cette demande ignoré l\'email, sinon vous avez 30 minutes pour vous rendre sur:<br/>' . $lien . '.<br/> Merci de votre confiance.<br/>L\'équipe de Party Share';

    dbConnect();
    //insertion dans la DB
    if (InsertionToken($token, $date, $idUtilisateur)) {

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
        $mail->Subject = 'Modification du mot de passe';
        $mail->Body = $message;
        $mail->AltBody = $message;

        $mail->send();
        $success = "Message envoyé, Merci de nous avoir contacté !";
        $_SESSION['MDP']['mailOk'] = 1;
    } catch (Exception $e) {
        $success = FALSE;
    }
}