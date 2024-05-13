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

        // Récupérer le code de l'agenda
        fetch('get_agenda_code.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP, status = ' + response.status);
            }
            return response.json(); // Récupérer la réponse au format JSON
        })
        .then(agendaCode => {
            console.log('Données de l\'agenda récupérées:', agendaCode);
            Agenda_deroulant(agendaData, agendaCode);
        })
        .catch(() => {
            // Si la récupération du code de l'agenda échoue, appeler init_agenda_session.php
            fetch('init_agenda_session.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur HTTP, status = ' + response.status);
                }
                return response.json(); // Récupérer la réponse au format JSON
            })
            .then(agendaCode => {
                console.log('Données de l\'agenda récupérées:', agendaCode);
                Agenda_deroulant(agendaData, agendaCode);
            })
            .catch(error => console.error('Erreur lors de la récupération des données de l\'agenda:', error));
        });
    })
    .catch(error => console.error('Erreur lors de la récupération des données des agendas :', error));
}
