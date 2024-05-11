<?php
session_start();

// Vérifier si la variable de session agenda_code est définie et égale à "J12VC4I"
if(isset($_SESSION['agenda_code']) && $_SESSION['agenda_code'] == "J12VC4I") {
    // Tableau de données de test
    $donnees_test = array(
        array(
            "id" => 1,
            "nom" => "Événement 1",
            "date" => "2024-05-11",
            "heure" => "10:00:00"
        ),
        array(
            "id" => 2,
            "nom" => "Événement 2",
            "date" => "2024-05-12",
            "heure" => "14:00:00"
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


