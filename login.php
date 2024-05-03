<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure PHPMailer
require 'vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$servername = "localhost:3306"; // Ou l'adresse de votre serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Démarrage de la session
session_start();

// Récupération des données du formulaire
$username = $_POST['username'];
$password = $_POST['password'];
$code = $_POST['code']; // Nouveau champ pour le code d'authentification

// Requête SQL pour vérifier les informations d'identification
$sql = "SELECT id, first_name, last_name FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Récupération des données de nom et prénom de l'utilisateur
    $row = $result->fetch_assoc();
    
    // Générer un code d'authentification aléatoire
    $code = mt_rand(100000, 999999);
    
    // Enregistrer le code dans la session
    $_SESSION['auth_code'] = $code;
    
    // Envoyer le code par e-mail
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'cycalender.site'; // Remplacez par le nom d'hôte SMTP de votre serveur
        $mail->SMTPAuth = true;
        $mail->Username = 'code.authentification@cycalender.site'; // Votre adresse e-mail
        $mail->Password = 'CYCalender1234'; // Votre mot de passe
        $mail->SMTPSecure = 'tls';
        $mail->Port = 465; // Port SMTP
        
        $mail->setFrom('code.authentification@cycalender.site', 'CYCalender');
        $mail->addAddress($_POST['username']);
        
        $mail->isHTML(true);
        $mail->Subject = 'Code d\'authentification';
        $mail->Body    = 'Votre code d\'authentification est : ' . $code;
        
        $mail->send();
        echo 'Un e-mail contenant le code d\'authentification a été envoyé.';
    } catch (Exception $e) {
        echo 'Erreur lors de l\'envoi de l\'e-mail : ', $mail->ErrorInfo;
    }

    // Redirection vers la vérification du code
    header('Location: verification_code.php');
    exit;
} else {
    echo "Identifiants incorrects";
}

$conn->close();
?>
