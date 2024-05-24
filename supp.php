<?php
// Définir les en-têtes pour indiquer que le contenu est au format JSON
header('Content-Type: application/json');

// Créer un tableau associatif avec la clé 'success' et la valeur true
$response = array('success' => true);

// Convertir le tableau en JSON
echo json_encode($response);
?>
