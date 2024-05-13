// Fonction pour récupérer les données des agendas depuis Liste_agenda.php
function getAgendaData2() {
    fetch('Liste_agenda.php') // Envoyer une requête HTTP à Liste_agenda.php pour récupérer les données des agendas
    .then(response => response.json()) // Convertir la réponse en JSON
    .then(data => {
        // Supprimer les guillemets autour des clés du tableau JSON
        const agendaData = data.map(item => {
            return {
                name: item.name,
                code: item.code
            };
        });
        //alert("Données des agendas récupérées avec succès :\n" + JSON.stringify(data));

        fetch('get_agenda_code.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP, status = ' + response.status);
            }
            return response.json(); // Récupérer la réponse au format JSON
        })
        .then(agendaCode => {
            console.log('Données de l\'agenda récupérées:', agendaCode);
            Agenda_deroulant(agendaData,agendaCode);
        })
        .catch(error => console.error('Erreur lors de la récupération des données de l\'agenda:', error));
    })
    .catch(error => console.error('Erreur lors de la récupération des données des agendas :', error));
}

// Fonction pour afficher les données des agendas dans un élément <select>
function Agenda_deroulant(agendaData, agendaCode) {
    // Sélectionner l'élément <select> dans le DOM
    const selectElement = document.getElementById("other-agendas");

    // Réinitialiser le contenu de l'élément <select>
    selectElement.innerHTML = "";

    // Parcourir le tableau des agendas et ajouter des options à l'élément <select>
    agendaData.forEach((agenda) => {
        // Créer un nouvel élément d'option
        const option = document.createElement("option");
        // Définir la valeur et le texte de l'option
        option.value = agenda.code;
        option.text = agenda.name;

        // Vérifier si l'agenda correspond à celui sélectionné par défaut
        if (agenda.code === agendaCode) {
            option.selected = true; // Sélectionner cette option par défaut
        }

        // Ajouter l'option à l'élément <select>
        selectElement.appendChild(option);
    });

    // Écouter l'événement de changement sur l'élément <select>
    selectElement.addEventListener("change", function() {
        // Récupérer la valeur de l'option sélectionnée
        const selectedCode = this.value;
        // Appeler la fonction change_agenda_session avec le code de l'agenda sélectionné
        change_agenda_session(selectedCode);
    });
}


function change_agenda_session(nvCode){
    fetch('change_agenda_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'code_agenda=' + encodeURIComponent(nvCode),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur HTTP, status = ' + response.status);
        }
        return response.text();
    })
    .then(newCode => {
        console.log('Nouveau code de session:', newCode);
        // Appeler initCalendar() après avoir changé le code de l'agenda
        getEvents();
        initCalendar();

    })
    .catch(error => console.error('Erreur lors de la modification de l\'agenda:', error));
}



