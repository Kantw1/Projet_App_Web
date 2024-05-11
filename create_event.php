<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$servername = "localhost";
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die(json_encode(array("error" => "Connection failed: " . $conn->connect_error)));
}

// Récupération des données envoyées en JSON
$data = json_decode(file_get_contents("php://input"), true);

session_start();

// Traitement des données et insertion dans la base de données
foreach ($data as $event_data) {
    $day = $event_data['day'];
    $month = $event_data['month'];
    $year = $event_data['year'];
    $events = $event_data['events'];

    foreach ($events as $event) {
        $title = $event['title'];
        $time = $event['time'];
        $event_time = date("H:i", strtotime($time));
        $description = isset($event['description']) ? $event['description'] : '';
        $place = isset($event['place']) ? $event['place'] : '';
        $creator = isset($_SESSION['username']) ? $_SESSION['username'] : '';
        $code_agenda = 0; // Définir agenda_code sur 0

        $sql_insert = "INSERT INTO events (day, month, year, title, event_time, description, place, creator, code_agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ssssssssi", $day, $month, $year, $title, $event_time, $description, $place, $creator, $code_agenda);
        if (!$stmt->execute()) {
            die(json_encode(array("error" => "Erreur lors de l'insertion des données: " . $stmt->error)));
        }
    }
}

// Fermeture de la connexion
$conn->close();

// Répondre avec succès
echo json_encode(array("message" => "Événements enregistrés avec succès"));
?>

