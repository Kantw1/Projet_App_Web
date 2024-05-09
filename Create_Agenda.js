const Agenda_name = document.querySelector(".Agenda_name"),
   Create_agenda = document.querySelector(".create-agenda");

   Create_agenda.addEventListener("click", () => {
    const AgendaName = Agenda_name.value;

    if (AgendaName === "") {
      alert("Please fill all the fields");
      return;
    }
    else {
        fetch('Create_Agenda.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'Agenda_name=' + encodeURIComponent(AgendaName),
        })
        .catch(error => console.error('Erreur lors de la creation de l\'agenda:', error));
    }
   });