<?php
// Connexion à la base de données
$servername = "localhost:3306"; // Ou l'adresse de votre serveur SQL
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
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Récupérer les codes des agendas associés à l'utilisateur
    $sql_user_agenda = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
    $result_user_agenda = $conn->query($sql_user_agenda);

    $Data = array(); // Tableau pour stocker les données des événements

    if ($result_user_agenda->num_rows > 0) {
        while ($row = $result_user_agenda->fetch_assoc()) {
            $agenda_code = $row["agenda_code"];
            $agenda_code = trim($agenda_code); // Supprimer les espaces éventuels

            // Récupérer les événements de l'agenda
            $sql_agenda_events = "SELECT * FROM events WHERE agenda_code = '$agenda_code'";
            $result_agenda_events = $conn->query($sql_agenda_events);

            if ($result_agenda_events->num_rows > 0) {
                while ($row_agenda_events = $result_agenda_events->fetch_assoc()) {
                    $event = array(
                        "day" => $row_agenda_events["day"],
                        "month" => $row_agenda_events["month"],
                        "year" => $row_agenda_events["year"],
                        "title" => $row_agenda_events["title"],
                        "time" => $row_agenda_events["event_time"],
                        "description" => $row_agenda_events["description"],
                        "place" => $row_agenda_events["place"],
                        "agenda_code" => $agenda_code
                    );
                    array_push($Data, $event);
                }
            }
        }     
        // Affichage du tableau des événements au format JSON
        header('Content-Type: application/json');
        echo json_encode($Data);
    } else {
        echo "Aucun agenda trouvé pour cet utilisateur.";
    }
} else {
    echo "L'utilisateur n'est pas connecté.";
}

$conn->close();
?>
