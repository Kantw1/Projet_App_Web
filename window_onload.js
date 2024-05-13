// Appel de la fonction pour afficher les données des agendas au chargement de la page
window.onload = function() {
    Promise.all([getUserData(), getAgendaData(), getAgendaData2()])
    .catch(error => {
        console.error('Une erreur s\'est produite lors du chargement des données:', error);
        // Redirection vers index.html en cas d'erreur
        window.location.href = 'index.html';
    });
};
