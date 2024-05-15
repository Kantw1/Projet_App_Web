<?php
// Connexion à la base de données
$servername = "localhost:3306"; // Ou l'adresse de votre serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
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

// Préparation de la requête
$query = "SELECT * FROM events WHERE agenda_code = :agenda_code";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':agenda_code', $agenda_code, PDO::PARAM_STR);
$stmt->execute();

$eventsArr = [];

// Récupération des résultats
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $event = [
        'day' => (int)$row['day'],
        'month' => (int)$row['month'],
        'year' => (int)$row['year'],
        'events' => [
            [
                'title' => $row['title'],
                'time' => $row['time'],
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

