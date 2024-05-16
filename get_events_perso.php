<?php
// Connexion à la base de données
$servername = "localhost"; // Ou l'adresse de votre serveur SQL
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
if (!isset($_SESSION['user_id'])) {
    die("Vous n'êtes pas connecté.");
}

$user_id = $_SESSION['user_id'];

// Récupérer tous les codes d'agenda auxquels l'utilisateur a accès
$query = "SELECT agenda_code FROM user_agenda WHERE id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$agenda_codes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Initialiser le tableau pour stocker tous les événements des agendas de l'utilisateur
$eventsArr = [];

// Boucle pour récupérer les événements de chaque agenda
foreach ($agenda_codes as $agenda_code) {
    // Préparation de la requête pour récupérer les événements de l'utilisateur
    $query = "SELECT * FROM events WHERE id = :id AND code_agenda = :code_agenda";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':code_agenda', $agenda_code, PDO::PARAM_STR);
    $stmt->execute();

    // Récupération des événements
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
}

// Envoie des données au format JSON
echo json_encode($eventsArr);
?>
