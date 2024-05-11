<?php
session_start();

// Vérifier si la variable de session agenda_code est définie et égale à "J12VC4I"
if(isset($_SESSION['agenda_code']) && $_SESSION['agenda_code'] == "J12VC4I") {
    // Tableau de données de test
    $donnees_test = [
        [
            "day" => 1,
            "month" => 5,
            "year" => 2024,
            "events" => [
                [
                    "title" => "Réunion importante",
                    "time" => "10h00 - 11h30",
                    "description" => "Réunion de lancement du nouveau projet",
                    "place" => "Salle de conférence",
                ],
                [
                    "title" => "Déjeuner avec l'équipe",
                    "time" => "12h30 - 14h00",
                    "description" => "Déjeuner avec les membres de l'équipe pour discuter des prochaines étapes",
                    "place" => "Restaurant XYZ",
                ],
            ],
        ],
        [
            "day" => 5,
            "month" => 5,
            "year" => 2024,
            "events" => [
                [
                    "title" => "Entretien d'évaluation",
                    "time" => "9h00 - 10h00",
                    "description" => "Entretien d'évaluation semestriel",
                    "place" => "Bureau",
                ],
            ],
        ],
    ];

    // Convertir le tableau en JSON
    $donnees_json = json_encode($donnees_test);

    // Envoyer le JSON en réponse
    echo $donnees_json;
} else {
    // Si la condition n'est pas remplie, renvoyer une erreur ou un message approprié
    echo "Accès non autorisé";
}
?>


