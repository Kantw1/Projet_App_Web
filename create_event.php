<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"), true);

session_start();

foreach ($data as $event_data) {
    $day = intval($event_data['day']); // Convertir en entier
    $month = intval($event_data['month']);
    $year = intval($event_data['year']);
    $events = $event_data['events'];

    foreach ($events as $event) {
        $title = $event['title'];
        $time = $event['time'];
        $event_time = date("H:i", strtotime($time));
        $description = isset($event['description']) ? $event['description'] : '';
        $place = isset($event['place']) ? $event['place'] : '';
        $creator = isset($_SESSION['username']) ? $_SESSION['username'] : ''; // Vérifier si la clé 'username' est définie
        $code_agenda = isset($_SESSION['agenda_code']) ? $_SESSION['agenda_code'] : ''; // Vérifier si la clé 'agenda_code' est définie

        $sql_insert = "INSERT INTO events (day, month, year, title, event_time, description, place, creator, code_agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("iiissssss", $day, $month, $year, $title, $event_time, $description, $place, $creator, $code_agenda);
        $stmt->execute();
    }
}

$conn->close();

echo json_encode(array("message" => "Événements enregistrés avec succès"));
?>



