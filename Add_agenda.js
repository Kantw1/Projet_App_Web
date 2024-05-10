const Agenda_code = document.querySelector(".agenda-code"),
   Add_agenda = document.querySelector(".add-agenda");

   Add_agenda.addEventListener("click", () => {
    const AgendaCode = Agenda_code.value;
    alert(AgendaCode);

    //alert(AgendaName);
    if (AgendaCode === "") {
      alert("Please fill all the fields");
      return;
    }
        fetch('Add_agenda.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'Agenda_code=' + encodeURIComponent(AgendaCode),
        })
        .then(response => response.text())
            .then(data => {
                getAgendaData_ajout();
                var nav = document.querySelector('.new-agenda');
                nav.style.display = nav.style.display === 'none' ? 'flex' : 'none';
            })
        .catch(error => console.error('Erreur lors de la creation de l\'agenda:', error));
   });