<?php
// Vérifie si l'utilisateur est connecté ou non, à adapter selon votre système d'authentification
session_start();

// Vérifie si les données POST sont présentes
$rawData = file_get_contents("php://input");
$eventData = json_decode($rawData, true);

if (!isset($eventData['title']) || !isset($eventData['time']) || !isset($eventData['description']) || !isset($eventData['place'])) {
    echo json_encode(array('success' => false, 'message' => 'Données invalides.'));
    exit;
}

$title = $eventData['title'];
$time = $eventData['time'];
$description = $eventData['description'];
$place = $eventData['place'];

// Connexion à la base de données (à remplacer par vos propres informations de connexion)
$servername = "localhost"; // Ou l'adresse de votre serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définir le mode d'erreur PDO sur exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
