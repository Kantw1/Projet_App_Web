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

// Récupération des données du formulaire
$first_name = $_POST['first-name'];
$last_name = $_POST['last-name'];
$username = $_POST['new-username'];
$password = $_POST['new-password'];

// Requête SQL pour insérer les données dans la table
$sql = "INSERT INTO users (first_name, last_name, username, password) VALUES ('$first_name', '$last_name', '$username', '$password')";

if ($conn->query($sql) === TRUE) {
    session_start();
    // Stockage du nom et du prénom de l'utilisateur dans la session
    $_SESSION['username'] = $username;
    $_SESSION['first_name'] = $first_name;
    $_SESSION['last_name'] = $last_name;

    $user_id = $conn->insert_id;
    // Requête SQL pour insérer l'entrée dans la table user_agendas
    $sql_insert_user_agenda = "INSERT INTO user_agendas (user_id) VALUES ('$user_id')";

    if ($conn->query($sql_insert_user_agenda) === TRUE) {
        $_SESSION['user_id_agenda'] = $user_id;
        header('Location: index.html');
    } else {
        header('Location: signup.html');
    }
} else {
    header('Location: signup.html');
}

$conn->close();
?>
