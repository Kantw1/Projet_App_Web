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

// Démarrer la session
session_start();
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Récupérer les codes des agendas associés à l'utilisateur
    $sql_user_agenda = "SELECT agenda_code FROM user_agenda WHERE user_id = '$user_id'";
    $result_user_agenda = $conn->query($sql_user_agenda);

    $Data = array(); // Tableau pour stocker les données des agendas

    if ($result_user_agenda->num_rows > 0) {
        while ($row = $result_user_agenda->fetch_assoc()) {
            $agenda_codes = explode(",", $row["agenda_code"]);

            foreach($agenda_codes as $agenda_code) {
                $agenda_code = trim($agenda_code); // Supprimer les espaces éventuels

                // Préparation de la requête pour récupérer les événements de l'agenda
                $sql_events = "SELECT * FROM events WHERE code_agenda = '$agenda_code'";
                $result_events = $conn->query($sql_events);

                $eventsArr = [];

                // Récupération des résultats
                while ($row = $result_events->fetch_assoc()) {
                    $event = [
                        'day' => (int)$row['day'],
                        'month' => (int)$row['month'],
                        'year' => (int)$row['year'],
                        'events' => [
                            [
                                'title' => $row['title'],
                                'time' => $row['event_time'], // Utilise le champ event_time pour l'heure
                                'description' => $row['description'],
                                'place' => $row['place']
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

                // Ajouter les événements de cet agenda au tableau global
                $Data = array_merge($Data, $eventsArr);
            }
        }

        // Envoie des données au format JSON
        echo json_encode($Data);
    } else {
        echo "Aucun agenda trouvé pour cet utilisateur.";
    }
} else {
    echo "L'utilisateur n'est pas connecté.";
}

$conn->close();
?>
