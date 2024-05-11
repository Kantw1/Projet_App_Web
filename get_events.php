<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['agenda_code'])) {
    http_response_code(403);
    die("Vous n'êtes pas autorisé à accéder à cet agenda.");
}

// Connexion à la base de données
$servername = "localhost";
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    http_response_code(500);
    die("Erreur de connexion à la base de données: " . $conn->connect_error);
}

$agenda_code = $_SESSION['agenda_code'];

// Récupérer les événements de la base de données
$sql = "SELECT day, month, year, title, event_time, description, place FROM events WHERE code_agenda = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $agenda_code);
$stmt->execute();
$result = $stmt->get_result();

// Créer un tableau pour stocker les événements
$eventsArr = [];

// Parcourir les résultats et les formatter
while ($row = $result->fetch_assoc()) {
    $event = array(
        "day" => $row["day"],
        "month" => $row["month"],
        "year" => $row["year"],
        "events" => array(
            array(
                "title" => $row["title"],
                "time" => $row["event_time"],
                "description" => $row["description"],
                "place" => $row["place"]
            )
        )
    );

    // Ajouter l'événement au tableau
    $eventsArr[] = $event;
}

// Fermeture de la connexion
$conn->close();

// Retourner les événements au format JSON
echo json_encode($eventsArr);
?>
