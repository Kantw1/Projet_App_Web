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

<?php
session_start(); // Démarrer la session pour accéder aux données de session

// Vérifier si l'utilisateur est connecté
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur à partir de la session
    $agenda_code = $_SESSION['agenda_code']; // Récupérer le code de l'agenda depuis la session

    // Requête SQL pour rechercher la ligne correspondant à l'utilisateur dans la table user_agendas
    $sql_select_user_agenda = "SELECT * FROM user_agendas WHERE user_id = '$user_id'";

    $result = $conn->query($sql_select_user_agenda);

    if ($result->num_rows > 0) {
        // Mettre à jour la ligne existante avec le nouveau agenda_code
        $sql_update_user_agenda = "UPDATE user_agendas SET agenda_code = '$agenda_code' WHERE user_id = '$user_id'";

        if ($conn->query($sql_update_user_agenda) === TRUE) {
            echo "Code d'agenda mis à jour avec succès dans la table user_agendas";
        } else {
            echo "Erreur lors de la mise à jour du code d'agenda dans la table user_agendas : " . $conn->error;
        }
    } else {
        echo "Aucune ligne trouvée pour l'utilisateur dans la table user_agendas";
    }
} else {
    echo "L'utilisateur n'est pas connecté.";
}

$conn->close();
?>

?>
