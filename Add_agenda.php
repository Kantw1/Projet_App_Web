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

// Vérification si le bouton "Ajouter un agenda" a été soumis
if (isset($_POST["Agenda_Code"])) {
    // Récupération du code de l'agenda depuis le formulaire
    $agenda_code = $_POST['Agenda_Code'];

    session_start();
    // Vérification si l'utilisateur est connecté
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Requête pour vérifier si le code généré est présent dans la base de données
        $sql_check = "SELECT id FROM agendas WHERE agenda_code = '$agenda_code'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            // Récupérer les codes des agendas associés à l'utilisateur
            $sql_user_agenda = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
            $result_user_agenda = $conn->query($sql_user_agenda);
            if ($result_user_agenda->num_rows > 0) {
                $row = $result_user_agenda->fetch_assoc();
                $agenda_codes = explode(",", $row["agenda_code"]);
                //vérifier si agenda_code appartient au tableau agenda_codes
                if (in_array($agenda_code, $agenda_codes)) {
                    $message = "Agenda déjà acquis";
                } else {
                    $message = "Ajout de l'agenda";
                }
            } else {
                // Si l'utilisateur n'a pas d'agenda, ajoutez simplement celui-ci
                $message = "Ajout de l'agenda";
            }
        } else {
            // Si le code n'existe pas, envoyer un message "Agenda non trouvé"
            $message = "Agenda non trouvé";
        }
    } else {
        // Si l'utilisateur n'est pas connecté, renvoyer un message d'erreur
        $message = "Utilisateur non connecté";
    }

    echo json_encode(array("message" => $message));
}

$conn->close();
?>
