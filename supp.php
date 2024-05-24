<?php
// Vérifie si l'utilisateur est connecté ou non, à adapter selon votre système d'authentification
session_start();

// Vérifie si les données POST sont présentes
if (!isset($_POST['title']) || !isset($_POST['time']) || !isset($_POST['description']) || !isset($_POST['place'])) {
    echo json_encode(array('success' => false, 'message' => 'Données manquantes.'));
    exit;
}

// Récupère les données POST
$title = $_POST['title'];
$time = $_POST['time'];
$description = $_POST['description'];
$place = $_POST['place'];

// Connexion à la base de données (à remplacer par vos propres informations de connexion)
$servername = "localhost:3306"; // Ou l'adresse de votre serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo json_encode(array('success' => false, 'message' => 'Erreur de connexion à la base de données.'));
    exit;
}

// Vérifie si l'événement existe pour le code agenda de la session
$code_agenda = $_SESSION['agenda_code']; // Assurez-vous que cette variable contient le code agenda de la session
$sql = "SELECT * FROM events WHERE title = :title AND event_time = :time AND description = :description AND place = :place AND code_agenda = :code_agenda";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':title', $title);
$stmt->bindParam(':time', $time);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':place', $place);
$stmt->bindParam(':code_agenda', $code_agenda);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    // L'événement n'existe pas pour le code agenda de la session, retourne une erreur
    echo json_encode(array('success' => false, 'message' => "L'événement n'existe pas pour ce code agenda."));
    exit;
}

// L'événement existe, le supprime de la table
$sql = "DELETE FROM events WHERE title = :title AND event_time = :time AND description = :description AND place = :place AND code_agenda = :code_agenda";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':title', $title);
$stmt->bindParam(':time', $time);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':place', $place);
$stmt->bindParam(':code_agenda', $code_agenda);
$stmt->execute();

echo json_encode(array('success' => true, 'message' => 'Événement supprimé avec succès.'));
?>
