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
if (!isset($_SESSION['agenda_code']) || !isset($_SESSION['user_id'])) {
    die("Vous n'êtes pas connecté.");
}

// Afficher les valeurs de agenda_code et user_id pour le débogage
echo "agenda_code: " . $_SESSION['agenda_code'] . "<br>";
echo "user_id: " . $_SESSION['user_id'] . "<br>";

$agenda_code = $_SESSION['agenda_code'];
$user_id = $_SESSION['user_id'];

// Préparation de la requête
$query = "SELECT * FROM events WHERE code_agenda = :code_agenda";

// Si l'agenda est personnel, récupérer tous les événements de tous les agendas de l'utilisateur
if ($agenda_code == $_SESSION['agenda_perso_code']) {
    $query = "SELECT * FROM events WHERE code_agenda IN (SELECT agenda_code FROM user_agenda WHERE user_id = :user_id)";
}

$stmt = $pdo->prepare($query);
$stmt->bindParam(':code_agenda', $agenda_code, PDO::PARAM_STR);

// Si l'agenda est personnel, lier également user_id à la requête
if ($agenda_code == $_SESSION['agenda_perso_code']) {
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
}

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

