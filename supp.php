<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$servername = "localhost:3306";
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    echo "false";
    exit();
}

// Récupération des données envoyées en POST
$eventTitle = $_POST['eventTitle'];

session_start();

// Vérifier si le code d'agenda est défini dans la session
if (!isset($_SESSION['agenda_code'])) {
    echo "false";
    exit();
}

$code_agenda = $_SESSION['agenda_code'];

if ($code_agenda == $_SESSION['agenda_perso_code']) {
    echo "false";
    exit();
}

// Préparer et exécuter la requête pour vérifier et supprimer l'événement
$sql_check = "SELECT * FROM events WHERE title = ? AND code_agenda = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $eventTitle, $code_agenda);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // L'événement existe, procéder à la suppression
    $sql_delete = "DELETE FROM events WHERE title = ? AND code_agenda = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ss", $eventTitle, $code_agenda);
    if ($stmt_delete->execute()) {
        // Suppression réussie
        echo "true";
    } else {
        // Erreur lors de la suppression
        echo "false";
    }
} else {
    // L'événement n'existe pas
    echo "false";
}

// Fermeture de la connexion
$conn->close();
?>
