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

    // Requête pour vérifier si le code généré est présent dans la base de données
    $sql_check = "SELECT * FROM user_agenda WHERE FIND_IN_SET('$agenda_code', agenda_code)";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        // Si le code existe, envoyer un message "Agenda trouvé"
        $message = array("message" => "Agenda déjà aquis");
    } else {
        // Si le code n'existe pas, envoyer un message "Agenda non trouvé"
        $message = array("message" => "Ajout de l'agenda ");
    }

} else {
    //afficher une alerte disant agenda non trouvé
    $message = "Agenda non trouvé";
}

echo json_encode(array("message" => $message));

}

$conn->close();
?>
