<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Inclure PHPMailer

$mail = new PHPMailer(true); // Créer une nouvelle instance de PHPMailer

try {
    // Paramètres SMTP
    $mail->isSMTP();
    $mail->Host = 'code.authentification@cycalender.site'; // Remplacez par le nom d'hôte SMTP de votre serveur
    $mail->SMTPAuth = true;
    $mail->Username = 'code.authentification@cycalender.site'; // Votre adresse e-mail
    $mail->Password = 'CYCalender1234'; // Votre mot de passe
    $mail->SMTPSecure = 'tls';
    $mail->Port = 465; // Port SMTP

    // Destinataire
    $mail->setFrom('votre_adresse@mail.com', 'Votre Nom');
    $mail->addAddress($_POST['email']);

    // Contenu de l'e-mail
    $mail->isHTML(true); // Définir le format de l'e-mail en HTML
    $mail->Subject = 'Code d\'authentification';
    $mail->Body    = 'Votre code d\'authentification est : ' . $code;

    $mail->send(); // Envoyer l'e-mail
    echo 'L\'e-mail a été envoyé avec succès !';
} catch (Exception $e) {
    echo 'Erreur lors de l\'envoi de l\'e-mail : ', $mail->ErrorInfo;
}
?>

