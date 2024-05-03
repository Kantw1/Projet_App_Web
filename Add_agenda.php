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

// Vérification de la méthode de requête
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification si le formulaire a été soumis
    if (isset($_POST["agenda-code"])) {
        // Récupération du code de l'agenda depuis le formulaire
        $agenda_code = $_POST['agenda-code'];

        // Vérification si le code existe dans la base de données
        $sql_check = "SELECT id FROM agendas WHERE agenda_code = '$agenda_code'";
        $result_check = $conn->query($sql_check);
        if ($result_check->num_rows > 0) {
            // Si le code existe
            // Stocker le code de l'agenda dans une variable de session
            session_start();
            $_SESSION['agenda_code'] = $agenda_code;
            echo "<script>alert('Code d\'agenda valide');</script>";

            include 'connection_agenda_user.php';
        } else {
            // Si le code n'existe pas, afficher une alerte
            echo "<script>alert('Code d\'agenda invalide');</script>";
        }
    }
}
?>
