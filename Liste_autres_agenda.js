// Fonction pour récupérer les données des agendas depuis Liste_agenda.php
function getAgendaData2() {
    const agendaData = [
        { name: "Agenda Personnel", code: "ABC123" },
        { name: "Agenda Ecole", code: "DEF456" },
        { name: "Agenda Travail", code: "GHI789" }
    ];

    Agenda_deroulant(agendaData);
}

// Fonction pour afficher les données des agendas dans un élément <select>
function Agenda_deroulant(agendaData) {
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
