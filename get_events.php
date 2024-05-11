<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['agenda_code'])) {
    http_response_code(403);
    die("Vous n'êtes pas autorisé à accéder à cet agenda.");
}

// Connexion à la base de données
// Remplacez les valeurs suivantes par vos propres paramètres de connexion
$host = 'localhost';
$user = 'votre_utilisateur';
$password = 'votre_mot_de_passe';
$database = 'votre_base_de_donnees';

$agenda_code = $_SESSION['agenda_code'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation de la requête
    $stmt = $pdo->prepare("SELECT * FROM events WHERE code_agenda = :code_agenda");
    $stmt->bindParam(':code_agenda', $agenda_code);
    $stmt->execute();

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retourner les événements au format JSON
    echo json_encode($events);
} catch (PDOException $e) {
    http_response_code(500);
    die("Erreur de base de données: " . $e->getMessage());
}
?>

