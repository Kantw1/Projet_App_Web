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

// Démarrer la session
session_start();
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Récupérer les codes des agendas associés à l'utilisateur
    $sql_user_agenda = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
    $result_user_agenda = $conn->query($sql_user_agenda);

    $Data = array(); // Tableau pour stocker les données des agendas

    if ($result_user_agenda->num_rows > 0) {
        $row = $result_user_agenda->fetch_assoc();
        $agenda_codes = explode(",", $row["agenda_code"]);

        foreach($agenda_codes as $agenda_code) {
            $agenda_code = trim($agenda_code); // Supprimer les espaces éventuels
            $sql_agenda_info = "SELECT * FROM events WHERE agenda_code = '$agenda_code'";
