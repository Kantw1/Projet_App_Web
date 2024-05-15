<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];
$agenda_perso_code = $_SESSION['agenda_perso_code'];

// Connexion à la base de données
$servername = "localhost"; // Ou l'adresse de votre serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer tous les codes d'agenda auxquels l'utilisateur a accès
    $stmt = $pdo->prepare('
        SELECT code_agenda
        FROM agenda_access
        WHERE user_id = :user_id
    ');
    $stmt->execute(['user_id' => $user_id]);
    $agenda_codes = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Inclure le code d'agenda personnel de l'utilisateur
    if ($agenda_perso_code) {
        $agenda_codes[] = $agenda_perso_code;
    }

    if (empty($agenda_codes)) {
        echo json_encode([]);
        exit();
    }

    // Récupérer tous les événements des agendas accessibles
    $placeholders = str_repeat('?,', count($agenda_codes) - 1) . '?';
    $stmt = $pdo->prepare("
        SELECT day, month, year, title, event_time, description, place
        FROM events
        WHERE code_agenda IN ($placeholders)
    ");
    $stmt->execute($agenda_codes);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatage des données
    $eventsArr = [];
    foreach ($events as $event) {
        $date = [
            'day' => $event['day'],
            'month' => $event['month'],
            'year' => $event['year'],
        ];

        $eventData = [
            'title' => $event['title'],
            'time' => $event['event_time'],
            'description' => $event['description'],
            'place' => $event['place'],
        ];

        // Vérifier si la date existe déjà dans $eventsArr
        $found = false;
        foreach ($eventsArr as &$item) {
            if ($item['day'] == $date['day'] && $item['month'] == $date['month'] && $item['year'] == $date['year']) {
                $item['events'][] = $eventData;
                $found = true;
                break;
            }
        }

        // Si la date n'existe pas encore, l'ajouter à $eventsArr
        if (!$found) {
            $eventsArr[] = [
                'day' => $date['day'],
                'month' => $date['month'],
                'year' => $date['year'],
                'events' => [$eventData],
            ];
        }
    }

    echo json_encode($eventsArr);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

