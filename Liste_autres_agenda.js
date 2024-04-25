// Sélectionner l'élément <select> dans le DOM
const selectElement = document.getElementById("other-agendas");

// Tableau d'objets représentant les agendas
const agendas = [
    { value: "agendaPerso", text: "Agenda Personnel", href: "#" },
    { value: "agenda2", text: "Agenda 2", href: "connectionlogin.html" },//faudra mettre le lien de l'agenda en question
    // Ajoutez d'autres objets pour d'autres agendas si nécessaire
];

// Parcourir le tableau des agendas et ajouter des options à l'élément <select>
agendas.forEach((agenda) => {
    // Créer un nouvel élément d'option
    const option = document.createElement("option");
    // Définir la valeur et le texte de l'option
    option.value = agenda.value;
    option.text = agenda.text;
    // Si un lien est fourni, définir l'attribut href de l'option
    if (agenda.href) {
        option.setAttribute("href", agenda.href);
    }
    // Ajouter l'option à l'élément <select>
    selectElement.appendChild(option);
});