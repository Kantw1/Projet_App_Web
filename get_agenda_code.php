<?php
session_start();

// Vérification si le code de l'agenda est présent dans la session
if (isset($_SESSION['agenda_code'])) {
    // Récupération du code de l'agenda depuis la session
    $agenda_code = $_SESSION['agenda_code'];
    // Répondre avec le code de l'agenda
    echo $agenda_code;
} else {
    // Si le code de l'agenda n'est pas défini dans la session, renvoyer une erreur
    header('HTTP/1.1 500 Internal Server Error');
    echo "Erreur: Code de l'agenda non défini dans la session";
}
?>
