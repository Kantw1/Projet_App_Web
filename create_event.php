<?php
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

foreach ($data as $event) {
    // Vérifier si toutes les clés sont présentes
    if (isset($event['day'], $event['month'], $event['year'], $event['title'], $event['time'])) {
        $day = intval($event['day']); // Convertir en entier
        $month = intval($event['month']);
        $year = intval($event['year']);
        $title = $event['title'];
        $time = $event['time'];
        $event_time = date("H:i", strtotime($time));
        $description = isset($event['description']) ? $event['description'] : '';
        $place = isset($event['place']) ? $event['place'] : '';
        $creator = $_SESSION['username'];
        $code_agenda = $_SESSION['agenda_code'];

        // Insertion de l'événement dans la table d'événements
        $sql_insert = "INSERT INTO events (day, month, year, title, event_time, description, place, creator, code_agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("iiissssss", $day, $month, $year, $title, $event_time, $description, $place, $creator, $code_agenda);
        $stmt->execute();
    } else {
        // Afficher un message d'erreur si des clés sont manquantes
        echo json_encode(array("error" => "Certaines clés sont manquantes dans les données d'événement"));
    }
}

$conn->close();

echo json_encode(array("message" => "Événements enregistrés avec succès"));
?>



