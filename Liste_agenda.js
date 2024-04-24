
// Fonction pour récupérer les données des agendas depuis Liste_agenda.php
function getAgendaData() {
    fetch('Liste_agenda.php') // Envoyer une requête HTTP à Liste_agenda.php pour récupérer les données des agendas
    .then(response => response.json()) // Convertir la réponse en JSON
    .then(Data => {
        // Afficher les données des agendas dans une alerte
        alert("Données des agendas récupérées avec succès :\n" + JSON.stringify(Data));
        // Stocker les données des agendas dans une constante
        const agendaData = Data;
        // Appeler la fonction pour afficher les données des agendas
        displayAgendaData(agendaData);
    })
    .catch(error => console.error('Erreur lors de la récupération des données des agendas :', error));
}
/*
const agendaData = [
    { name: "Agenda Personnel", code: "ABC123" },
    { name: "Agenda Ecole", code: "DEF456" },
    { name: "Agenda Travail", code: "GHI789" }
];
*/

// Fonction pour afficher les données des agendas dans la liste
function displayAgendaData(agendaData) {
    const agendaList = document.getElementById("agenda-list");
    if (agendaList) {
        agendaData.forEach(function(agenda, index) {
            const li = document.createElement("li");
            const nameInput = document.createElement("input");
            nameInput.setAttribute("type", "text");
            nameInput.setAttribute("class", "agenda-name");
            nameInput.setAttribute("value", agenda.name);
            if (index === 0) {
                nameInput.setAttribute("readonly", true); // Rendre le premier nom d'agenda en lecture seule
            }
            const codeInput = document.createElement("input");
            codeInput.setAttribute("type", "text");
            codeInput.setAttribute("class", "agenda-code");
            if (index !== 0) {
                codeInput.setAttribute("value", agenda.code); // Ajouter la valeur du code pour les autres agendas
            } else {
                codeInput.style.display = "none"; // Masquer le code pour le premier agenda
            }
            codeInput.setAttribute("readonly", true);
            const deleteButton = document.createElement("button");
            deleteButton.innerText = "🗑️"; // Poubelle emoji
            deleteButton.setAttribute("class", "delete-agenda-button");
            li.appendChild(nameInput);
            li.appendChild(codeInput);
            li.appendChild(deleteButton);
            agendaList.appendChild(li);
        });
    }
}



// Appel de la fonction pour récupérer les données des agendas au chargement de la page
//window.onload = getAgendaData;
