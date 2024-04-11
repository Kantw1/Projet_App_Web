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

session_start(); // Démarrer la session pour accéder aux données de session

// Vérifier si l'utilisateur est connecté
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur à partir de la session

    // Récupérer le code de l'agenda à partir des données du formulaire ou d'où vous le récupérez
    $agenda_code = $_SESSION['agenda_code']; // Par exemple, récupérer à partir d'un formulaire

    // Requête SQL pour insérer l'entrée dans la table user_agendas
    $sql_insert_user_agenda = "INSERT INTO user_agendas (user_id, agenda_code) VALUES ('$user_id', '$agenda_code')";

    if ($conn->query($sql_insert_user_agenda) === TRUE) {
        echo "Code d'agenda ajouté avec succès à la table user_agendas";
    } else {
        echo "Erreur lors de l'ajout du code d'agenda à la table user_agendas : " . $conn->error;
    }
} else {
    echo "L'utilisateur n'est pas connecté.";
}

$conn->close();
?>
