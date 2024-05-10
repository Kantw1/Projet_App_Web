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

    // Démarrer la session
    session_start();
    // Récupérer l'ID de l'utilisateur depuis la session
    $user_id = $_SESSION['user_id'];

    // Requête SQL pour vérifier si l'Agenda_code appartient à user_Agenda
    $sql = "SELECT COUNT(*) as count FROM user_agenda WHERE user_id = '$user_id' AND FIND_IN_SET('$agenda_code', agenda_code)";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $count = $row["count"];
        if ($count > 0) {
            // Le code Agenda_code appartient à l'utilisateur
            echo "Agenda déjà existant";
        } else {
            // Le code Agenda_code n'appartient pas à l'utilisateur
            echo "Agenda ajouté";
        }
    } else {
        // Erreur lors de l'exécution de la requête SQL
        echo "Erreur lors de la vérification de l'agenda.";
    }

    $conn->close();
}
?>
