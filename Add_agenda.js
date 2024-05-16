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
        if(data.message) {
            alert(data.message); // Afficher le message de réponse
        } else {
            // Effectuer la deuxième requête uniquement si la première réussit
            fetch('connection_agenda_user.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            })
            .then(response => response.json())
            .then(data => {
                // Traiter la réponse de la deuxième requête ici si nécessaire
                // Dans votre cas, vous pouvez appeler getAgendaData_ajout() ici
                getAgendaData_ajout();
                
                // Afficher ou masquer l'élément .new-agenda
                var nav = document.querySelector('.new-agenda');
                nav.style.display = nav.style.display === 'none' ? 'flex' : 'none';
            })
            .catch(error => console.error('Erreur lors de la connexion de l\'agenda à l\'utilisateur:', error));
        }
    })
    .catch(error => console.error('Erreur lors de la création de l\'agenda:', error));
});
