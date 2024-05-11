<?php
session_start();

// Vérifier si la variable de session agenda_code est définie et égale à "J12VC4I"
if(isset($_SESSION['agenda_code']) && $_SESSION['agenda_code'] == "J12VC4I") {
    // Tableau de données de test
    $donnees_test = array(
        array(
            "day" => "11",
            "month" => "5",
            "year" => "2024",
            "title" => "Événement 1",
            "event_time" => "10:00:00",
            "description" => "Description de l'événement 1",
            "place" => "Lieu de l'événement 1",
            "creator" => "Créateur de l'événement 1",
            "code_agenda" => "J12VC4I"
        ),
        array(
            "day" => "12",
            "month" => "5",
            "year" => "2024",
            "title" => "Événement 2",
            "event_time" => "14:00:00",
            "description" => "Description de l'événement 2",
            "place" => "Lieu de l'événement 2",
            "creator" => "Créateur de l'événement 2",
            "code_agenda" => "J12VC4I"
        ),
        // Ajoutez plus de données au besoin
    );

    // Convertir le tableau en JSON
    $donnees_json = json_encode($donnees_test);

    // Envoyer le JSON en réponse
    echo $donnees_json;
} else {
    // Si la condition n'est pas remplie, renvoyer une erreur ou un message approprié
    echo "Accès non autorisé";
}
?>

