<?php
// Connexion à la base de données
$servername = "localhost"; // Adresse du serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "CYCalenderB";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Démarrer la session
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Vous n'êtes pas connecté.");
}

$user_id = $_SESSION['user_id'];

// Assurez-vous que $agenda_codes est défini
if (!isset($_POST['agenda_codes'])) {
    die("Aucun code d'agenda n'a été fourni.");
}

$agenda_codes = $_POST['agenda_codes'];

if (!is_array($agenda_codes)) {
    die("Les codes d'agenda doivent être sous forme de tableau.");
}

$Data = array();

foreach($agenda_codes as $agenda_code) {
    $agenda_code = trim($agenda_code); // Supprimer les espaces éventuels
    $sql_agenda_info = "SELECT * FROM events WHERE agenda_code = '$agenda_code'";
    $result_agenda_info = $conn->query($sql_agenda_info);

    if ($result_agenda_info->num_rows > 0) {
        while ($row_agenda_info = $result_agenda_info->fetch_assoc()) {
            $event = array(
                'day' => $row_agenda_info['day'],
                'month' => $row_agenda_info['month'],
                'year' => $row_agenda_info['year'],
                'title' => $row_agenda_info['title'],
                'time' => $row_agenda_info['event_time'],
                'description' => $row_agenda_info['description'],
                'place' => $row_agenda_info['place']
            );
            array_push($Data, $event);
        }
    }
}

// Affichage du tableau agendaData au format JSON
header('Content-Type: application/json');
echo json_encode($Data);

$conn->close();
?>
