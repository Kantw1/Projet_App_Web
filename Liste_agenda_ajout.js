// Fonction pour récupérer les données des agendas depuis Liste_agenda.php
function getAgendaData_ajout() {
    fetch('Liste_agenda.php') // Envoyer une requête HTTP à Liste_agenda.php pour récupérer les données des agendas
    .then(response => response.json()) // Convertir la réponse en JSON
    .then(Data => {
        // Supprimer les guillemets autour des clés du tableau JSON
        const lastAgenda = Data[Data.length - 1]; // Récupérer le dernier membre du tableau
        const agendaData = [{ name: lastAgenda.name, code: lastAgenda.code }]; // Créer un tableau avec seulement le dernier nom et le dernier code
        // Appeler la fonction pour afficher les données du dernier agenda
        displayLastAgendaData_ajout(agendaData);
    })
    .catch(error => console.error('Erreur lors de la récupération des données des agendas :', error));
}

// Fonction pour afficher les données du dernier agenda dans la liste
function displayLastAgendaData_ajout(agendaData) {
    const agendaList = document.getElementById("agenda-list");

    // Supprimer tous les éléments de la liste
    while (agendaList.firstChild) {
        agendaList.removeChild(agendaList.firstChild);
    }

    if (agendaList && agendaData.length > 0) {
        const agenda = agendaData[0]; // Récupérer le premier (et unique) membre du tableau
        const li = document.createElement("li");
        const nameInput = document.createElement("input");
        nameInput.setAttribute("type", "text");
        nameInput.setAttribute("class", "agenda-name");
        nameInput.setAttribute("value", agenda.name);
        nameInput.setAttribute("readonly", true); // Rendre le nom d'agenda en lecture seule
        const codeInput = document.createElement("input");
        codeInput.setAttribute("type", "text");
        codeInput.setAttribute("class", "agenda-code");
        codeInput.setAttribute("value", agenda.code); // Ajouter la valeur du code pour le dernier agenda
        codeInput.setAttribute("readonly", true);

        li.appendChild(nameInput);
        li.appendChild(codeInput);

        agendaList.appendChild(li);
    }
}
