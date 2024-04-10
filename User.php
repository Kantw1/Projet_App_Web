<?php
// Connexion à la base de données
$servername = "localhost"; // Ou l'adresse de votre serveur SQL
$username = "votre_nom_utilisateur";
$password = "votre_mot_de_passe";
$dbname = "votre_base_de_donnees";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération des données de l'utilisateur
$sql = "SELECT nom, prenom, username FROM utilisateur WHERE id = 1"; // Remplacez "1" par l'ID de l'utilisateur
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Création d'un tableau associatif pour stocker les données de l'utilisateur
    $user_data = $result->fetch_assoc();
    // Conversion des données en format JSON
    $json_data = json_encode($user_data);
    // Affichage des données sous forme de réponse HTTP
    header('Content-Type: application/json');
    echo $json_data;
} else {
    echo "Utilisateur non trouvé.";
}

$conn->close();
?>
