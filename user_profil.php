<?php
session_start();

// Vérification si l'utilisateur est connecté et si ses informations sont disponibles dans la session
if (isset($_SESSION['username'])) {
    $firstName = $_SESSION['first_name'];
    $lastName = $_SESSION['last_name'];
    $username = $_SESSION['username'];

    // Affichage des informations de l'utilisateur dans le HTML
    ?>
    <div class="user-data">
        <h2>Données de l'utilisateur</h2>
        <!-- Insérez ici les données de l'utilisateur -->
        <p><strong>Nom :</strong> <?php echo $lastName; ?></p>
        <p><strong>Prénom :</strong> <?php echo $firstName; ?></p>
        <p><strong>Nom d'utilisateur:</strong> <?php echo $username; ?></p>
    </div>
    <?php
} else {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: login.php');
    exit;
}
?>





// Fonction pour récupérer les données de l'utilisateur depuis User.php
function getUserData() {
    fetch('User.php') // Envoyer une requête HTTP à User.php pour récupérer les données de l'utilisateur
    .then(response => response.json()) // Convertir la réponse en JSON
    .then(data => {
        // Stocker les données de l'utilisateur dans une constante
        const userData = data;
        // Appeler la fonction pour afficher les données de l'utilisateur
        displayUserData(userData);
    })
    .catch(error => console.error('Erreur lors de la récupération des données de l\'utilisateur:', error));
}
