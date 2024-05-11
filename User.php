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

// Vérifier si l'ID de session existe
if(isset($_SESSION['user_id'])) {
    // Construire un tableau associatif contenant les données de l'utilisateur
    $userData = array(
        'user_id' => $_SESSION['user_id'],
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username']
    );



    // Convertir le tableau en format JSON et le renvoyer
    header('Content-Type: application/json');
    echo json_encode($userData);
} else {
    echo "Session utilisateur non trouvée.";
}
?>
