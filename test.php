<?php
// Démarrage de la session PHP
session_start();

// Simuler la récupération de l'agenda_code à partir de la session (à remplacer par la méthode réelle)
$agenda_code = "example_agenda_code";

// Tableau d'exemple de données d'événements
$events = array(
    array(
        "day" => "10",
        "month" => "5",
        "year" => "2024",
        "title" => "Exemple d'événement 1",
        "event_time" => "10:00",
        "description" => "Description de l'événement 1",
        "place" => "Lieu de l'événement 1",
        "creator" => "Créateur de l'événement 1",
        "code_agenda" => $agenda_code
    ),
    array(
        "day" => "15",
        "month" => "5",
        "year" => "2024",
        "title" => "Exemple d'événement 2",
        "event_time" => "14:00",
        "description" => "Description de l'événement 2",
        "place" => "Lieu de l'événement 2",
        "creator" => "Créateur de l'événement 2",
        "code_agenda" => $agenda_code
    ),
    array(
        "day" => "20",
        "month" => "5",
        "year" => "2024",
        "title" => "Exemple d'événement 3",
        "event_time" => "18:30",
        "description" => "Description de l'événement 3",
        "place" => "Lieu de l'événement 3",
        "creator" => "Créateur de l'événement 3",
        "code_agenda" => $agenda_code
    )
);

// Encodage des données au format JSON
echo json_encode($events);
?>

