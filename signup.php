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

// Récupération des données du formulaire
$first_name = $_POST['first-name'];
$last_name = $_POST['last-name'];
$username = $_POST['new-username'];
$password = $_POST['new-password'];

// Requête SQL pour insérer les données dans la table
$sql = "INSERT INTO users (first_name, last_name, username, password) VALUES ('$first_name', '$last_name', '$username', '$password')";

if ($conn->query($sql) === TRUE) {
    header('Location: index.html');
} else {
    header('Location: signup.html');
}

$conn->close();
?>
