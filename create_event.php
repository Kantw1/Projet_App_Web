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

// Traitement des données et insertion dans la base de données
foreach ($data as $event) {
    $date = $event['year'] . "-" . $event['month'] . "-" . $event['day'];
    foreach ($event['events'] as $subEvent) {
        $title = $subEvent['title'];
        $start_time = date("H:i", strtotime($subEvent['time'])); // Conversion de l'heure au format 'HH:MM'
        $end_time = date("H:i", strtotime($subEvent['time'])); // Si nécessaire, ajustez pour l'heure de fin
        $description = isset($subEvent['description']) ? $subEvent['description'] : '';
        $place = isset($subEvent['place']) ? $subEvent['place'] : '';
        $creator = "Nom du créateur"; // Ajustez selon votre système d'authentification
        $code_agenda = "Code de l'agenda"; // Ajustez selon votre système d'authentification

        // Insertion de l'événement dans la table d'événements
        $sql_insert = "INSERT INTO events (day, title, start_time, end_time, description, place, creator, code_agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ssssssss", $date, $title, $start_time, $end_time, $description, $place, $creator, $code_agenda);
        $stmt->execute();
    }
}

// Fermeture de la connexion
$conn->close();

// Répondre avec succès
echo json_encode(array("message" => "Events saved successfully"));
?>

