
// Fonction pour récupérer les données des agendas depuis Liste_agenda.php
function getAgendaData() {
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
                const shareButton = document.createElement("button");
                shareButton.innerText = "Partager"; // Texte du bouton "Partager"
                shareButton.setAttribute("class", "share-agenda-button");
                shareButton.addEventListener("click", function() {
                    const codeAgenda = this.parentElement.querySelector('.agenda-code').value;
                    mail(codeAgenda);
});
                li.appendChild(deleteButton, li);
                li.appendChild(shareButton);
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
    // Sélectionner l'élément <select> dans le DOM
    const selectElement = document.getElementById("other-agendas");

    // Réinitialiser le contenu de l'élément <select>
    function attendre() {
        console.log("Attente terminée !");
      }
    getAgendaData2();
}

// Fonction pour envoyer le mot de passe par email
function mail(codeAgenda) {
    fetch('get_username.php')
    .then(response => response.json()) // Convertir la réponse en JSON
    .then(data => {
      // Vérification si l'e-mail existe dans les données
      if (data.username) {
        var mailto_link = 'mailto:?subject=' + data.username + '%20vous%20a%20partagé%20un%20agenda.&body=' + data.username + '%20vous%20a%20partagé%20un%20agenda,%20le%20code%20de%20l\'agenda%20est%20:%20' + codeAgenda + '%0D%0A%0D%0ARendez-vous%20sur%20https://cycalender.site';
        window.open(mailto_link,'_blank');
      } else {
        console.error("L'username de session n'a pas été trouvé.");
      }
    })
    .catch(error => console.error('Erreur lors de la récupération de l\'username de session :', error));
    
}