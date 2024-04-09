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

// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté
if(isset($_SESSION['username'])) {
    // Récupération de l'ID de l'utilisateur depuis la session
    $username = $_SESSION['username'];
    $sql_user_id = "SELECT id FROM users WHERE username = '$username'";
    $result_user_id = $conn->query($sql_user_id);
    
    if($result_user_id->num_rows > 0) {
        $row_user_id = $result_user_id->fetch_assoc();
        $user_id = $row_user_id['id'];
        
        // Vérification de la méthode de requête
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Vérification si le bouton "Créer un nouvel agenda" a été soumis
            if (isset($_POST["create-agenda"])) {
                // Récupération du nom de l'agenda depuis le formulaire
                $agenda_name = $_POST['Agenda_name'];
    
                // Génération d'un code d'agenda unique
                $uniqueCode = generateUniqueCode();
    
                // Vérification si le code généré est déjà présent dans la base de données
                $sql_check = "SELECT id FROM agendas WHERE agenda_code = '$uniqueCode'";
                $result_check = $conn->query($sql_check);
                if ($result_check->num_rows > 0) {
                    // Si le code existe déjà, générer un nouveau code jusqu'à ce qu'un code unique soit trouvé
                    while ($result_check->num_rows > 0) {
                        $uniqueCode = generateUniqueCode();
                        $sql_check = "SELECT id FROM agendas WHERE agenda_code = '$uniqueCode'";
                        $result_check = $conn->query($sql_check);
                    }
                }
    
                // Insertion du nouvel agenda dans la base de données
                $sql_insert_agenda = "INSERT INTO agendas (agenda_name, agenda_code) VALUES ('$agenda_name', '$uniqueCode')";
                if ($conn->query($sql_insert_agenda) === TRUE) {
                    // Récupération de l'ID de l'agenda inséré
                    $agenda_id = $conn->insert_id;
                    
                    // Insertion de l'association entre l'utilisateur et l'agenda dans la table user_agendas
                    $sql_insert_user_agenda = "INSERT INTO user_agendas (user_id, agenda_id) VALUES ('$user_id', '$agenda_id')";
                    if ($conn->query($sql_insert_user_agenda) === TRUE) {
                        // Redirection vers la page d'agenda
                        header('Location: Agenda.html');
                        exit;
                    } else {
                        $alert_message = "Erreur lors de l'insertion de l'association utilisateur-agenda : " . $conn->error;
                    }
                } else {
                    $alert_message = "Erreur lors de la création de l'agenda : " . $conn->error;
                }
            }
        }
    } else {
        // Utilisateur non trouvé dans la base de données
        $alert_message = "Utilisateur non trouvé dans la base de données.";
    }
} else {
    // Utilisateur non connecté
    $alert_message = "Vous devez être connecté pour créer un nouvel agenda.";
}
?>

<!-- Affichage de l'alerte en JavaScript -->
<script>
    // Vérification si un message d'alerte est présent
    <?php if (!empty($alert_message)) { ?>
        alert("<?php echo $alert_message; ?>");
    <?php } ?>
</script>
