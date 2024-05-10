<?php
// Connexion à la base de données
$servername = "localhost"; // Ou l'adresse de votre serveur SQL
$username = "cycalguj";
$password = "CYCalender1234";
$dbname = "votre_base_de_données";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérification si les données du formulaire ont été envoyées
if (isset($_POST["event_name"])) {
    // Récupération des données du formulaire
    $day = $_POST['day'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $title = $_POST['event_name'];
    $start_time = $_POST['event_time_from'];
    $end_time = $_POST['event_time_to'];
    $description = $_POST['event_description'];
    $place = $_POST['event_place'];
    $creator = "Nom du créateur"; // A ajuster selon votre système d'authentification
    $code_agenda = "Code de l'agenda"; // A ajuster selon votre système d'authentification

    // Insertion de l'événement dans la table d'événements
    $sql_insert = "INSERT INTO events (day, month, year, title, start_time, end_time, description, place, creator, code_agenda) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Préparation de la requête
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iiisssssss", $day, $month, $year, $title, $start_time, $end_time, $description, $place, $creator, $code_agenda);

    // Exécution de la requête
    if ($stmt->execute()) {
        echo "Événement ajouté avec succès.";
    } else {
        echo "Erreur lors de l'ajout de l'événement : " . $conn->error;
    }

    // Fermeture de la connexion
    $stmt->close();
}

// Fermeture de la connexion
$conn->close();
?>



