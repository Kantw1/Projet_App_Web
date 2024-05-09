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

// Fonction pour générer un code d'événement aléatoire
function generateUniqueCode($length = 7) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// Déclaration de la variable pour stocker le message d'alerte
$alert_message = "";

// Vérification de la méthode de requête
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification si le formulaire pour créer un événement a été soumis
    if (isset($_POST["create-event"])) {
        // Récupération des données de l'événement depuis le formulaire
        $event_name = $_POST['event_name'];
        $event_start_time = $_POST['event_start_time'];
        $event_end_time = $_POST['event_end_time'];
        $event_description = $_POST['event_description'];
        $event_place = $_POST['event_place'];
        $code_agenda = $_POST['code_agenda']; // Ajout du code_agenda

        // Génération d'un code d'événement unique
        $uniqueCode = generateUniqueCode();

        // Insertion de l'événement dans la base de données
        $sql_insert = "INSERT INTO events (title, time_from, time_to, description, place, creator, code_agenda) VALUES ('$event_name', '$event_start_time', '$event_end_time', '$event_description', '$event_place', 'creator_name', '$code_agenda')";
        if ($conn->query($sql_insert) === TRUE) {
            $alert_message = "Nouvel événement créé avec succès avec le code : " . $uniqueCode;
        } else {
            $alert_message = "Erreur lors de la création de l'événement : " . $conn->error;
        }
    }
}
?>

<!-- Affichage de l'alerte en JavaScript -->
<script>
    // Vérification si un message d'alerte est présent
    <?php if (!empty($alert_message)) { ?>
        alert("<?php echo $alert_message; ?>");
    <?php } ?>
</script>
