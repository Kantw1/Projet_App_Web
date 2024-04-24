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

        // Supprimer l'agenda de la table agenda_user
        $sql_delete_agenda = "DELETE FROM agenda_user WHERE user_id = '$user_id' AND agenda_code = '$code_agenda'";
        if ($conn->query($sql_delete_agenda) === TRUE) {
            $response = array("success" => true, "message" => "Agenda supprimé avec succès.");
            echo json_encode($response);
        } else {
            $response = array("success" => false, "message" => "Erreur lors de la suppression de l'agenda: " . $conn->error);
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
