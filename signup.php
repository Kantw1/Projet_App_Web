<?php
// Connexion à la base de données
$servername = "localhost";
$username = "votre_nom_utilisateur";
$password = "votre_mot_de_passe";
$dbname = "agenda_collaboratif";

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
    echo "Nouvel utilisateur créé avec succès!";
} else {
    echo "Erreur: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
