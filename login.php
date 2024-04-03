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

// Requête SQL pour vérifier les informations de connexion
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // L'utilisateur est connecté avec succès
    echo "Connexion réussie!";
} else {
    // L'utilisateur n'est pas trouvé dans la base de données
    echo "Identifiants incorrects. Veuillez réessayer.";
}

$conn->close();
?>
