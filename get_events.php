<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['agenda_code'])) {
    http_response_code(403);
    die("Vous n'êtes pas autorisé à accéder à cet agenda.");
}

// Connexion à la base de données
$servername = "localhost:3306"; // Ou l'adresse de votre serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

$agenda_code = $_SESSION['agenda_code'];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation de la requête
    $stmt = $pdo->prepare("SELECT * FROM events WHERE code_agenda = :code_agenda");
    $stmt->bindParam(':code_agenda', $agenda_code);
    $stmt->execute();

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Structure de données pour stocker les événements par jour, mois et année
    $eventsArr = array();

    foreach ($events as $event) {
        $day = $event['day'];
        $month = $event['month'];
        $year = $event['year'];
        $title = $event['title'];
        $time = $event['event_time'];
        $description = $event['description'];
        $place = $event['place'];

        // Vérifier si les valeurs essentielles ne sont pas vides
        if (!empty($title) && !empty($time)) {
            // Créer un nouvel événement
            $newEvent = array(
                'title' => $title,
                'time' => $time,
                'description' => $description,
                'place' => $place
            );

            // Vérifier si le jour existe déjà dans le tableau des événements
            $found = false;
            foreach ($eventsArr as &$dayObj) {
                if ($dayObj['day'] == $day && $dayObj['month'] == $month && $dayObj['year'] == $year) {
                    // Ajouter l'événement au jour existant
                    $dayObj['events'][] = $newEvent;
                    $found = true;
                    break;
                }
            }

            // Si le jour n'existe pas encore, le créer
            if (!$found) {
                $eventsArr[] = array(
                    'day' => $day,
                    'month' => $month,
                    'year' => $year,
                    'events' => array($newEvent)
                );
            }
        }
    }

    // Retourner les événements au format JSON
    echo json_encode($eventsArr);
} catch (PDOException $e) {
    http_response_code(500);
    die("Erreur de base de données: " . $e->getMessage());
}
?>
