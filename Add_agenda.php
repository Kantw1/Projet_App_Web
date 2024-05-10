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

    // Vérification si le code généré est présent dans la base de données
$sql_check = "SELECT id FROM agendas WHERE agenda_code = '$agenda_code'";
$result_check = $conn->query($sql_check);
if ($result_check->num_rows > 0) {
    // Si le code existe afficher une alerte : agenda trouvé
    $message = "Agenda trouvé";
} else {
    //afficher une alerte disant agenda non trouvé
    $message = "Agenda non trouvé";
}

echo json_encode(array("message" => $message));

}

$conn->close();
?>
