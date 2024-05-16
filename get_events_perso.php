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

// Démarrer la session
session_start();
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

// Récupérer les codes des agendas associés à l'utilisateur
$sql_user_agenda = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
$result_user_agenda = $conn->query($sql_user_agenda);

$Data = array(); // Tableau pour stocker les données des agendas

if ($result_user_agenda->num_rows > 0) {
    $row = $result_user_agenda->fetch_assoc();
    $agenda_codes = explode(",", $row["agenda_code"]);
        
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

// Si des événements sont trouvés, les renvoyer au format JSON
if (!empty($Data)) {
    header('Content-Type: application/json');
    echo json_encode($Data);
} else {
    // Aucun agenda trouvé pour cet utilisateur
    $error_message = "Aucun agenda trouvé pour cet utilisateur.";
    header('Content-Type: application/json');
    echo json_encode(['error' => $error_message]);
}

$conn->close();
?>
