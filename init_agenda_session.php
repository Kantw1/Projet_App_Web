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

// Démarrer la session
session_start();
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Récupérer les codes des agendas associés à l'utilisateur
    $sql_user_agenda = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
    $result_user_agenda = $conn->query($sql_user_agenda);

    $Data = array(); // Tableau pour stocker les données des agendas

    if ($result_user_agenda->num_rows > 0) {
        $row = $result_user_agenda->fetch_assoc();
        $agenda_codes = explode(",", $row["agenda_code"]);

        // Initialiser $_SESSION['agenda_code'] avec le premier agenda du tableau
        $_SESSION['agenda_code'] = $agenda_codes[0];
    }
}
