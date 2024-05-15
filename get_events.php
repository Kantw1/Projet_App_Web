<?php
// Connexion à la base de données
$servername = "localhost:3306"; // Ou l'adresse de votre serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// Vérifie si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['agenda_code'])) {
    die("Vous n'êtes pas connecté.");
}

$agenda_code = $_SESSION['agenda_code'];

// Vérifie si l'agenda est personnel ou non
$query_agenda_type = "SELECT agenda_perso_code FROM user_agenda WHERE agenda_code = :agenda_code";
$stmt_agenda_type = $pdo->prepare($query_agenda_type);
$stmt_agenda_type->bindParam(':agenda_code', $agenda_code, PDO::PARAM_STR);
$stmt_agenda_type->execute();
$agenda_type = $stmt_agenda_type->fetchColumn();

if ($agenda_type == $_SESSION['agenda_perso_code']) {
    // Si l'agenda est personnel, récupérer tous les événements de tous les agendas de l'utilisateur
    $query_events = "SELECT * FROM events WHERE code_agenda IN (SELECT agenda_code FROM user_agenda WHERE user_id = :user_id)";
    $stmt_events = $pdo->prepare($query_events);
    $stmt_events->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
} else {
    // Sinon, récupérer uniquement les événements de l'agenda spécifié
    $query_events = "SELECT * FROM events WHERE code_agenda = :code_agenda";
    $stmt_events = $pdo->prepare($query_events);
    $stmt_events->bindParam(':code_agenda', $agenda_code, PDO::PARAM_STR);
}

$stmt_events->execute();

$eventsArr = [];

// Récupération des résultats
while ($row = $stmt_events->fetch(PDO::FETCH_ASSOC)) {
    $event = [
        'day' => (int)$row['day'],
        'month' => (int)$row['month'],
        'year' => (int)$row['year'],
        'events' => [
            [
                'title' => $row['title'],
                'time' => $row['event_time'], // Utilise le champ event_time pour l'heure
                'description' => $row['description'],
                'place' => $row['place']
            ]
        ]
    ];

    // Recherche si un événement existe déjà pour ce jour
    $index = array_search($event['day'], array_column($eventsArr, 'day'));

    // Si un événement existe pour ce jour, ajoute le nouvel événement à cet index
    if ($index !== false) {
        array_push($eventsArr[$index]['events'], $event['events'][0]);
    } else {
        // Sinon, ajoute le nouvel événement à $eventsArr
        array_push($eventsArr, $event);
    }
}

// Envoie des données au format JSON
echo json_encode($eventsArr);
?>

