const Agenda_name = document.querySelector(".Agenda_name"),
   Create_agenda = document.querySelector(".create-agenda");

   Create_agenda.addEventListener("click", () => {
    const AgendaName = Agenda_name.value;

    //alert(AgendaName);
    if (AgendaName === "") {
      alert("Please fill all the fields");
      return;
    }
        fetch('Create_Agenda.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'Agenda_name=' + encodeURIComponent(AgendaName),
        })
        .then(response => response.text())
            .then(data => {
                //alert(data);
                getAgendaDatas();
            })
        .catch(error => console.error('Erreur lors de la creation de l\'agenda:', error));
   });