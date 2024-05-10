const Agenda_code = document.querySelector(".agenda-code"),
   Add_agenda = document.querySelector(".add-agenda");

Add_agenda.addEventListener("click", () => {
    const AgendaCode = Agenda_code.value;
    if (AgendaCode === "") {
        alert("Veuillez remplir tous les champs");
        return;
    }
    fetch('Add_agenda.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'Agenda_Code=' + encodeURIComponent(AgendaCode),
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message); // Afficher le message de réponse
        if(data.message === "Agenda trouvé") {
            // Code à exécuter si l'agenda est trouvé
        } else {
            // Code à exécuter si l'agenda n'est pas trouvé
        }
    })
    .catch(error => console.error('Erreur lors de la création de l\'agenda:', error));    

    var nav = document.querySelector('.new-agenda');
    nav.style.display = nav.style.display === 'none' ? 'flex' : 'none';
});
