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

    $Data = array(); // Tableau pour stocker les données des agendas

    if ($result_user_agenda->num_rows > 0) {
        while($row = $result_user_agenda->fetch_assoc()) {
            // Pour chaque code d'agenda de l'utilisateur, récupérer son nom et son code
            $agenda_code = $row["agenda_code"];
            $sql_agenda_info = "SELECT agenda_name FROM agendas WHERE agenda_code = '$agenda_code'";
            $result_agenda_info = $conn->query($sql_agenda_info);
            if ($result_agenda_info->num_rows > 0) {
                $row_agenda_info = $result_agenda_info->fetch_assoc();
                $agenda = array(
                    "name" => $row_agenda_info["agenda_name"],
                    "code" => $agenda_code
                );
                array_push($Data, $agenda);
            }
        }       
        // Affichage du tableau agendaData au format JSON
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
