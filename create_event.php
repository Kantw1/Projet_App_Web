<?php
// Connexion à la base de données
$servername = "localhost";
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupération des données envoyées en JSON
$data = json_decode(file_get_contents("php://input"), true);

session_start();

// Traitement des données et insertion dans la base de données
foreach ($data as $event) {
    $day = $event['day'];
    $month = $event['month'];
    $year = $event['year'];
    $title = $event['title'];

    // Récupération de l'heure à partir de la chaîne de caractères
    $time = $event['time'];
    $times = explode(" - ", $time);
    $event_time = date("H:i", strtotime($times[0])); // Heure de l'événement

    $description = isset($event['description']) ? $event['description'] : ''; // Vérification de la description
    $place = isset($event['place']) ? $event['place'] : ''; // Vérification du lieu
    $creator = $_SESSION['username']; // Ajustez selon votre système d'authentification
    $code_agenda = $_SESSION['agenda_code']; // Ajustez selon votre système d'authentification

    // Insertion de l'événement dans la table d'événements
    $sql_insert = "INSERT INTO events (day, month, year, title, event_time, description, place, creator, code_agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iisssssss", $day, $month, $year, $title, $event_time, $description, $place, $creator, $code_agenda);
    $stmt->execute();
}

// Fermeture de la connexion
$conn->close();

// Répondre avec succès
echo json_encode(array("message" => "Events saved successfully"));
?>


