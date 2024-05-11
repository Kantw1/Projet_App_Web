<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de configuration de la base de données
    require_once "config.php";
    
    // Récupérer les données du formulaire
    $eventTitle = $_POST["eventTitle"];
    $eventTimeFrom = $_POST["eventTimeFrom"];
    $eventTimeTo = $_POST["eventTimeTo"];
    $eventDescription = $_POST["eventDescription"];
    $eventPlace = $_POST["eventPlace"];
    
    // Préparer la requête SQL pour insérer l'événement dans la base de données
    $sql = "INSERT INTO events (title, time_from, time_to, description, place) VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $mysqli->prepare($sql)) {
        // Lier les paramètres à la requête
        $stmt->bind_param("sssss", $eventTitle, $eventTimeFrom, $eventTimeTo, $eventDescription, $eventPlace);
        
        // Exécuter la requête
        if ($stmt->execute()) {
            echo "L'événement a été ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout de l'événement : " . $stmt->error;
        }
        
        // Fermer la déclaration
        $stmt->close();
    }
    
    // Fermer la connexion à la base de données
    $mysqli->close();
}
?>
