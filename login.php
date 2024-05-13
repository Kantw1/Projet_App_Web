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

// Démarrage de la session
session_start();

// Récupération des données du formulaire
$username = $_POST['username'];
$password = $_POST['password'];

// Requête SQL pour vérifier les informations d'identification
$sql = "SELECT id, first_name, last_name FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Récupération des données de nom et prénom de l'utilisateur
    $row = $result->fetch_assoc();
    
    // Stockage de l'identifiant de l'utilisateur dans une session
    $_SESSION['username'] = $username;
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['last_name'] = $row['last_name'];
    $_SESSION['user_id'] = $row['id']; // Stockage de l'ID de l'utilisateur
    
    // Création d'un cookie sécurisé pour se souvenir de l'utilisateur
    $cookie_name = "user_id";
    $cookie_value = $row['id'];
    $cookie_expire = time() + (86400 * 30); // Expire dans 30 jours
    $cookie_path = "/"; // Chemin du cookie
    $cookie_domain = "cycalender.site"; // Votre domaine
    $cookie_secure = true; // Cookie sécurisé (HTTPS uniquement)
    $cookie_httponly = true; // Cookie accessible uniquement via HTTP
    setcookie($cookie_name, $cookie_value, $cookie_expire, $cookie_path, $cookie_domain, $cookie_secure, $cookie_httponly);
    
    // Redirection vers l'agenda collaboratif
    include 'init_agenda_session.php';
    header('Location: Agenda.html');
    exit;
} else {
    echo "Identifiants incorrects";
}

$conn->close();
?>