// Sélectionner l'élément <select> dans le DOM
const selectElement = document.getElementById("other-agendas");

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
        Agenda_deroulant(data);
    })
    .catch(error => console.error('Erreur lors de la récupération des données des agendas :', error));
}

// Fonction pour afficher les données des agendas dans un élément <select>
function Agenda_deroulant(agendaData) {
    // Réinitialiser le contenu de l'élément <select>
    selectElement.innerHTML = "";

    // Parcourir le tableau des agendas et ajouter des options à l'élément <select>
    agendaData.forEach((agenda) => {
        // Créer un nouvel élément d'option
        const option = document.createElement("option");
        // Définir la valeur et le texte de l'option
        option.value = agenda.code;
        option.text = agenda.name;
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
