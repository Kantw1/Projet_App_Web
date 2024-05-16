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

    // Vérifier si le code_agenda est passé en POST
    if(isset($_POST['code_agenda'])) {
        $code_agenda = $_POST['code_agenda'];

        // Récupérer les codes d'agenda associés à l'utilisateur
        $sql_user_agenda = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
        $result_user_agenda = $conn->query($sql_user_agenda);

        if ($result_user_agenda->num_rows > 0) {
            // Récupérer la chaîne de texte contenant les codes d'agenda de l'utilisateur
            $row = $result_user_agenda->fetch_assoc();
            $agenda_text = $row["agenda_code"];

            // Supprimer le code de l'agenda de la chaîne de texte
            $agenda_text = str_replace($code_agenda, '', $agenda_text);
            
            // Supprimer les virgules en double
            $agenda_text = preg_replace('/,(\s*,)+/', ',', $agenda_text);

            // Mettre à jour la table user_agenda avec la nouvelle chaîne de texte
            $sql_update_agenda = "UPDATE user_agenda SET agenda_code = '$agenda_text' WHERE user_id = '$user_id'";
            if ($conn->query($sql_update_agenda) === TRUE) {
                $response = array("success" => true, "message" => "Agenda supprimé avec succès.");
                $_SESSION['agenda_code'] = $code_agenda;
                echo json_encode($response);
            } else {
                $response = array("success" => false, "message" => "Erreur lors de la mise à jour de la liste des agendas: " . $conn->error);
                echo json_encode($response);
            }
        } else {
            $response = array("success" => false, "message" => "Aucun agenda trouvé pour cet utilisateur.");
            echo json_encode($response);
        }
    } else {
        $response = array("success" => false, "message" => "Code d'agenda manquant.");
        echo json_encode($response);
    }
} else {
    $response = array("success" => false, "message" => "L'utilisateur n'est pas connecté.");
    echo json_encode($response);
}

$conn->close();
?>
