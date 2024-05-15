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
$agenda_code_personnel = $_SESSION['agenda_perso_code'];


if ($agenda_code == $agenda_code_personnel) {
    // Si l'agenda_code est égal à l'agenda_code_personnel, appeler get_events_perso.php
    include('get_events_perso.php');
    echo "Agenda personnel : " . $agenda_code_personnel;
    exit();
}

// Préparation de la requête
$query = "SELECT * FROM events WHERE code_agenda = :code_agenda";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':code_agenda', $agenda_code, PDO::PARAM_STR);
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

