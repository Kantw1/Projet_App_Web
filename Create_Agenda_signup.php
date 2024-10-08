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

// Fonction pour générer un code d'agenda aléatoire
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

echo "Le bloc else est atteint.";

    $agenda_name = 'Agenda Perso';

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
        $sql_insert = "INSERT INTO agendas (agenda_name, agenda_code) VALUES ('$agenda_name', '$uniqueCode')";
        if ($conn->query($sql_insert) === TRUE) {
            // Démarrer la session
            session_start();

            // Stocker le code de l'agenda dans une variable de session
            $_SESSION['agenda_code'] = $uniqueCode;

            include 'connection_agenda_user.php';

            $alert_message = "Nouvel agenda créé avec succès avec le code : " . $uniqueCode;
        } else {
            $alert_message = "Erreur lors de la création de l'agenda : " . $conn->error;
        }
?>