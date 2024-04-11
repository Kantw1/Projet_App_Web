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

session_start(); // Démarrer la session pour accéder aux données de session

// Vérifier si l'utilisateur est connecté
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // Récupérer l'ID de l'utilisateur à partir de la session
    $agenda_code = $_SESSION['agenda_code']; // Récupérer le code de l'agenda depuis la session

    // Requête SQL pour récupérer la valeur actuelle de agenda_code pour cet utilisateur
    $sql_select_agenda_code = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
    $result = $conn->query($sql_select_agenda_code);

    if ($result->num_rows > 0) {
        // Si une ligne est trouvée, mettre à jour le agenda_code existant
        $row = $result->fetch_assoc();
        $existing_agenda_code = $row['agenda_code'];

        if (!empty($existing_agenda_code)) {
            // Ajouter le nouveau code après une virgule
            $new_agenda_code = $existing_agenda_code . ',' . $agenda_code;
        } else {
            // Si agenda_code est vide, utiliser simplement le nouveau code
            $new_agenda_code = $agenda_code;
        }

        // Mettre à jour la ligne existante avec le nouveau agenda_code
        $sql_update_user_agenda = "UPDATE user_agenda SET agenda_code = '$new_agenda_code' WHERE user_id = '$user_id'";


        if ($conn->query($sql_update_user_agenda) === TRUE) {
            echo "Code d'agenda mis à jour avec succès dans la table user_agenda";
        } else {
            echo "Erreur lors de la mise à jour du code d'agenda dans la table user_agenda : " . $conn->error;
        }
    } else {
        // Aucune ligne trouvée pour cet utilisateur, insérer un nouveau enregistrement
        $sql_insert_user_agenda = "INSERT INTO user_agenda (user_id, agenda_code) VALUES ('$user_id', '$agenda_code')";

        if ($conn->query($sql_insert_user_agenda) === TRUE) {
            echo "Nouveau code d'agenda ajouté avec succès dans la table user_agenda";
        } else {
            echo "Erreur lors de l'ajout du nouveau code d'agenda dans la table user_agenda : " . $conn->error;
        }
    }
} else {
    echo "L'utilisateur n'est pas connecté.";
}

$conn->close();
?>
