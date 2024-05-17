<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
$servername = "localhost:3306";
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

// Vérifier si le code d'agenda est défini dans la session
if (!isset($_SESSION['agenda_code'])) {
    die(json_encode(array("error" => "Code d'agenda non défini")));
}

$code_agenda = $_SESSION['agenda_code'];

if ($code_agenda == $_SESSION['agenda_perso_code']) {
    exit();
}

// Traitement des données et insertion dans la base de données
foreach ($data as $event_data) {
    $day = $event_data['day'];
    $month = $event_data['month'];
    $year = $event_data['year'];
    $events = $event_data['events'];

    foreach ($events as $event) {
        $title = $event['title'];
        $time = $event['time'];
        $event_time = $time; // Gardez la valeur d'origine comme une chaîne de caractères
        $description = isset($event['description']) ? $event['description'] : '';
        $place = isset($event['place']) ? $event['place'] : '';
        $creator = isset($_SESSION['username']) ? $_SESSION['username'] : '';

        // Vérifier si l'événement existe déjà
        $sql_check = "SELECT * FROM events WHERE day = ? AND month = ? AND year = ? AND title = ? AND event_time = ? AND description = ? AND place = ? AND code_agenda = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("iiisssss", $day, $month, $year, $title, $event_time, $description, $place, $code_agenda);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows == 0) {
            // L'événement n'existe pas, l'insérer dans la base de données
            $sql_insert = "INSERT INTO events (day, month, year, title, event_time, description, place, creator, code_agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("iiissssss", $day, $month, $year, $title, $event_time, $description, $place, $creator, $code_agenda);
            if (!$stmt->execute()) {
                die(json_encode(array("error" => "Erreur lors de l'insertion des données: " . $stmt->error)));
            }
        }
    }
}

// Fermeture de la connexion
$conn->close();

// Répondre avec succès, inclure le code d'agenda
echo json_encode(array("message" => "Événements enregistrés avec succès", "agenda_code" => $code_agenda));
?>
