// Fonction pour récupérer les données de l'utilisateur depuis user.php
function getUserData() {
    fetch('User.php')
    .then(response => response.json())
    .then(data => {
        // Stocker les données de l'utilisateur dans une constante
        const userData = data;
        // Appeler la fonction pour afficher les données de l'utilisateur
        displayUserData(userData);
    })
    .catch(error => console.error('Erreur lors de la récupération des données de l\'utilisateur:', error));
}

// Fonction pour afficher les données de l'utilisateur dans la classe 'user-data'
function displayUserData(userData) {
    const userContainer = document.querySelector('.user-data');
    if (userContainer) {
        userContainer.innerHTML = `
            <h2>Données de l'utilisateur</h2>
            <p><strong>Nom :</strong> ${userData.nom}</p>
            <p><strong>Prénom :</strong> ${userData.prenom}</p>
            <p><strong>Nom d'utilisateur :</strong> ${userData.username}</p>
        `;
    }
}

// Appel de la fonction pour récupérer les données de l'utilisateur au chargement de la page
window.onload = getUserData;
