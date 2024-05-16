<?php
// Connexion à la base de données
$servername = "localhost"; // Adresse du serveur SQL
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
if (!isset($_SESSION['user_id'])) {
    die("Vous n'êtes pas connecté.");
}

$user_id = $_SESSION['user_id'];

// Requête SQL pour récupérer les agendas personnels de l'utilisateur
$sql = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
$result = $conn->query($sql);

$eventsArr = [];

// Si des agendas personnels sont trouvés
if ($result->num_rows > 0) {
    // Boucler à travers chaque agenda personnel
    while ($row = $result->fetch_assoc()) {
        $agenda_code = $row['agenda_code'];
        // Requête SQL pour récupérer les événements de cet agenda
        $eventsSql = "SELECT * FROM events WHERE code_agenda = '$agenda_code'";
        $eventsResult = $conn->query($eventsSql);
        // Boucler à travers les événements de cet agenda et les ajouter à $eventsArr
        while ($eventRow = $eventsResult->fetch_assoc()) {
            $event = [
                'day' => (int)$eventRow['day'],
                'month' => (int)$eventRow['month'],
                'year' => (int)$eventRow['year'],
                'events' => [
                    [
                        'title' => $eventRow['title'],
                        'time' => $eventRow['event_time'],
                        'description' => $eventRow['description'],
                        'place' => $eventRow['place']
                    ]
                ]
            ];
            $eventsArr[] = $event;
        }
    }
}

// Envoyer les données au format JSON
echo json_encode($eventsArr);

