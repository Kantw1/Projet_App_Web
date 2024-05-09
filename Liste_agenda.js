
// Fonction pour récupérer les données des agendas depuis Liste_agenda.php
function getAgendaDatas() {
    fetch('Liste_agenda.php') // Envoyer une requête HTTP à Liste_agenda.php pour récupérer les données des agendas
    .then(response => response.json()) // Convertir la réponse en JSON
    .then(Data => {
        // Supprimer les guillemets autour des clés du tableau JSON
        const agendaData = Data.map(item => {
            return {
                name: item.name,
                code: item.code
            };
        });
        // Afficher les données des agendas dans une alerte
        //alert("Données des agendas récupérées avec succès :\n" + JSON.stringify(Data));
        // Appeler la fonction pour afficher les données des agendas
        displayAgendaDatas(agendaData);
       
        
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
function displayAgendaDatas(agendaData) {
    const agendaList = document.getElementById("agenda-list");

    // Supprimer tous les éléments de la liste
    while (agendaList.firstChild) {
        agendaList.removeChild(agendaList.firstChild);
    }

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

            li.appendChild(nameInput);
            li.appendChild(codeInput);
            if (index !== 0) {
                const deleteButton = document.createElement("button");
                deleteButton.innerText = "🗑️"; // Poubelle emoji
                deleteButton.setAttribute("class", "delete-agenda-button");
                deleteButton.addEventListener("click", function() {
                    const codeAgenda = this.parentElement.querySelector('.agenda-code').value;
                    deleteAgenda(codeAgenda, li);
                });
                li.appendChild(deleteButton, li);
            }
            agendaList.appendChild(li);
        });
    }
}

// Fonction pour supprimer un agenda
function deleteAgenda(codeAgenda, liElement) {
    if (confirm("Voulez-vous vraiment supprimer cet agenda ?")) {
        fetch('Supprimer_agenda_de_agenda_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'code_agenda=' + encodeURIComponent(codeAgenda),
        })
        .then(response => response.json())
        .then(data => {
            //alert(data.message);
            // Supprimer l'agenda de la liste
            liElement.remove();
        })
        .catch(error => console.error('Erreur lors de la suppression de l\'agenda:', error));
    }
}

// Appel de la fonction pour récupérer les données des agendas au chargement de la page
//window.onload = getAgendaData;
