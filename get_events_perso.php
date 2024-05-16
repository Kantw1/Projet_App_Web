<?php
// Connexion à la base de données
$servername = "localhost"; // Ou l'adresse de votre serveur SQL
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

    $eventsArr = []; // Tableau pour stocker les événements

    if ($result_user_agenda->num_rows > 0) {
        while ($row = $result_user_agenda->fetch_assoc()) {
            $agenda_code = $row['agenda_code'];

            // Récupérer les événements pour chaque agenda de l'utilisateur
            $sql_agenda_info = "SELECT * FROM events WHERE code_agenda = '$agenda_code'";
            $result_agenda_info = $conn->query($sql_agenda_info);

            if ($result_agenda_info->num_rows > 0) {
                while ($event_row = $result_agenda_info->fetch_assoc()) {
                    $event = [
                        'day' => (int)$event_row['day'],
                        'month' => (int)$event_row['month'],
                        'year' => (int)$event_row['year'],
                        'events' => [
                            [
                                'title' => $event_row['title'],
                                'time' => $event_row['event_time'],
                                'description' => $event_row['description'],
                                'place' => $event_row['place']
                            ]
                        ]
                    ];

                    // Recherche si un événement existe déjà pour ce jour
                    $index = array_search($event['day'], array_column($eventsArr, 'day'));

                    // Si un événement existe pour ce jour, ajoute le nouvel événement à cet index
                    if ($index !== false) {
                        array_push($eventsArr[$index]['events'], $event['events'][0]);
                    } else {
                        // Sinon, ajoute le nouvel événement à $eventsArr
                        array_push($eventsArr, $event);
                    }
                }
            }
        }
    }

    // Envoie des données au format JSON
    echo json_encode($eventsArr);
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>
