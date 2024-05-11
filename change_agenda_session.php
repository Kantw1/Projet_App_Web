<?php
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

// Vérification si le code de l'agenda a été envoyé en tant que POST
if (isset($_POST["code_agenda"])) {
    // Récupération du code de l'agenda depuis la requête POST
    $agenda_code = $_POST['code_agenda'];

    session_start();
    // Enregistrement du code de l'agenda dans la session
    $_SESSION['agenda_code'] = $agenda_code;
}
?>
