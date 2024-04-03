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
$username = $_POST['username'];
$password = $_POST['password'];

// Requête SQL pour vérifier les informations d'identification
$sql = "SELECT id, first_name, last_name FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Démarrage de la session
    session_start();
    // Récupération des données de nom et prénom de l'utilisateur
    $row = $result->fetch_assoc();
    // Stockage de l'identifiant de l'utilisateur dans une session
    $_SESSION['username'] = $username;
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['last_name'] = $row['last_name'];
    // Redirection vers l'agenda collaboratif
    header('Location: Agenda.html');
    exit;
} else {
    echo "Identifiants incorrects";
}

$conn->close();
?>
