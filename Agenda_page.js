const calendar = document.querySelector(".calendar"),
  date = document.querySelector(".date"),
  daysContainer = document.querySelector(".days"),
  prev = document.querySelector(".prev"),
  next = document.querySelector(".next"),
  todayBtn = document.querySelector(".today-btn"),
  gotoBtn = document.querySelector(".goto-btn"),
  dateInput = document.querySelector(".date-input"),
  eventDay = document.querySelector(".event-day"),
  eventDate = document.querySelector(".event-date"),
  eventsContainer = document.querySelector(".events"),
  addEventBtn = document.querySelector(".add-event"),
  addEventWrapper = document.querySelector(".add-event-wrapper "),
  addEventCloseBtn = document.querySelector(".close "),
  addEventTitle = document.querySelector(".event-name "),
  addEventFrom = document.querySelector(".event-time-from "),
  addEventTo = document.querySelector(".event-time-to "),
  addEventDescription = document.querySelector(".event-description "),
  addEventPlace = document.querySelector(".event-place "),
  addEventSubmit = document.querySelector(".add-event-btn ");

let today = new Date();
let activeDay;
let month = today.getMonth();
let year = today.getFullYear();

const months = [
  "Janvier",
  "Février",
  "Mars",
  "Avril",
  "Mai",
  "Juin",
  "Juillet",
  "Août",
  "Septembre",
  "Octobre",
  "Novembre",
  "Décembre",
];

// const eventsArr = [
//   {
//     day: 13,
//     month: 11,
//     year: 2022,
//     events: [
//       {
//         title: "Event 1 lorem ipsun dolar sit genfa tersd dsad ",
//         time: "10:00 AM",
//       },
//       {
//         title: "Event 2",
//         time: "11:00 AM",
//       },
//     ],
//   },
// ];

const eventsArr = [];
getEvents();
console.log(eventsArr);

//function to add days in days with class day and prev-date next-date on previous month and next month days and active on today
function initCalendar() {
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const prevLastDay = new Date(year, month, 0);
  const prevDays = prevLastDay.getDate();
  const lastDate = lastDay.getDate();   
  const day = firstDay.getDay() - 1;
  const nextDays = 7 - lastDay.getDay() - 1;

  date.innerHTML = months[month] + " " + year;

  let days = "";

  for (let x = day; x > 0; x--) {
    days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
  }

  for (let i = 1; i <= lastDate; i++) {
    //check if event is present on that day
    let event = false;
    eventsArr.forEach((eventObj) => {
      if (
        eventObj.day === i &&
        eventObj.month === month + 1 &&
        eventObj.year === year
      ) {
        event = true;
      }
    });
    if (
      i === new Date().getDate() &&
      year === new Date().getFullYear() &&
      month === new Date().getMonth()
    ) {
      activeDay = i;
      getActiveDay(i);
      updateEvents(i);
      if (event) {
        days += `<div class="day today active event">${i}</div>`;
      } else {
        days += `<div class="day today active">${i}</div>`;
      }
    } else {
      if (event) {
        days += `<div class="day event">${i}</div>`;
      } else {
        days += `<div class="day ">${i}</div>`;
      }
    }
  }

  for (let j = 1; j <= nextDays; j++) {
    days += `<div class="day next-date">${j}</div>`;
  }
  daysContainer.innerHTML = days;
  addListner();
}

//function to add month and year on prev and next button
function prevMonth() {
  month--;
  if (month < 0) {
    month = 11;
    year--;
  }
  initCalendar();
}

function nextMonth() {
  month++;
  if (month > 11) {
    month = 0;
    year++;
  }
  initCalendar();
}

prev.addEventListener("click", prevMonth);
next.addEventListener("click", nextMonth);

initCalendar();

//function to add active on day
function addListner() {
  const days = document.querySelectorAll(".day");
  days.forEach((day) => {
    day.addEventListener("click", (e) => {
      getActiveDay(e.target.innerHTML);
      updateEvents(Number(e.target.innerHTML));
      activeDay = Number(e.target.innerHTML);
      //remove active
      days.forEach((day) => {
        day.classList.remove("active");
      });
      //if clicked prev-date or next-date switch to that month
      if (e.target.classList.contains("prev-date")) {
        prevMonth();
        //add active to clicked day afte month is change
        setTimeout(() => {
          //add active where no prev-date or next-date
          const days = document.querySelectorAll(".day");
          days.forEach((day) => {
            if (
              !day.classList.contains("prev-date") &&
              day.innerHTML === e.target.innerHTML
            ) {
              day.classList.add("active");
            }
          });
        }, 100);
      } else if (e.target.classList.contains("next-date")) {
        nextMonth();
        //add active to clicked day afte month is changed
        setTimeout(() => {
          const days = document.querySelectorAll(".day");
          days.forEach((day) => {
            if (
              !day.classList.contains("next-date") &&
              day.innerHTML === e.target.innerHTML
            ) {
              day.classList.add("active");
            }
          });
        }, 100);
      } else {
        e.target.classList.add("active");
      }
    });
  });
}

todayBtn.addEventListener("click", () => {
  today = new Date();
  month = today.getMonth();
  year = today.getFullYear();
  initCalendar();
});

dateInput.addEventListener("input", (e) => {
  dateInput.value = dateInput.value.replace(/[^0-9/]/g, "");
  if (dateInput.value.length === 2) {
    dateInput.value += "/";
  }
  if (dateInput.value.length > 7) {
    dateInput.value = dateInput.value.slice(0, 7);
  }
  if (e.inputType === "deleteContentBackward") {
    if (dateInput.value.length === 3) {
      dateInput.value = dateInput.value.slice(0, 2);
    }
  }
});

gotoBtn.addEventListener("click", gotoDate);

function gotoDate() {
  console.log("here");
  const dateArr = dateInput.value.split("/");
  if (dateArr.length === 2) {
    if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length === 4) {
      month = dateArr[0] - 1;
      year = dateArr[1];
      initCalendar();
      return;
    }
  }
  alert("Invalid Date");
}

//function get active day day name and date and update eventday eventdate
function getActiveDay(date) {
  const day = new Date(year, month, date);
  const options = { weekday: 'long', timeZone: 'Europe/Paris' }; // Définir l'option pour afficher le jour en français
  const dayName = day.toLocaleString('fr-FR', options); // Formater le jour en français
  eventDay.innerHTML = dayName;
  eventDate.innerHTML = date + " " + months[month] + " " + year;
}


//function update events when a day is active
function updateEvents(date) {
  let events = "";
  eventsArr.forEach((event) => {
    if (
      date === event.day &&
      month + 1 === event.month &&
      year === event.year
    ) {
      event.events.forEach((event) => {
        events += `<div class="event">
            <div class="title">
              <i class="fas fa-circle"></i>
              <h3 class="event-title">${event.title}</h3>
            </div>
            <div class="event-time">
              <span class="event-time">${event.time}</span>
            </div>
            <div class="event-description hidden">
              <span class="event-description">${event.description}</span>
            </div>
            <div class="event-position hidden">
              <span class="event-place">${event.place}</span>
            </div>
        </div>`;
      });
    }
  });
  if (events === "") {
    events = `<div class="no-event">
            <h3>Pas d'événement</h3>
        </div>`;
  }
  eventsContainer.innerHTML = events;
  saveEvents();
}

//function to add event
addEventBtn.addEventListener("click", () => {
  addEventWrapper.classList.toggle("active");
});

addEventCloseBtn.addEventListener("click", () => {
  addEventWrapper.classList.remove("active");
});

document.addEventListener("click", (e) => {
  if (e.target !== addEventBtn && !addEventWrapper.contains(e.target)) {
    addEventWrapper.classList.remove("active");
  }
});

//allow 50 chars in eventtitle
addEventTitle.addEventListener("input", (e) => {
  addEventTitle.value = addEventTitle.value.slice(0, 60);
});

addEventDescription.addEventListener("input", (e) => {
  addEventDescription.value = addEventDescription.value.slice(0, 600);
});

addEventPlace.addEventListener("input", (e) => {
  addEventPlace.value = addEventPlace.value.slice(0, 600);
});

function defineProperty() {
  var osccred = document.createElement("div");
  osccred.style.position = "absolute";
  osccred.style.bottom = "0";
  osccred.style.right = "0";
  osccred.style.fontSize = "10px";
  osccred.style.color = "#ccc";
  osccred.style.fontFamily = "sans-serif";
  osccred.style.padding = "5px";
  osccred.style.background = "#fff";
  osccred.style.borderTopLeftRadius = "5px";
  osccred.style.borderBottomRightRadius = "5px";
  osccred.style.boxShadow = "0 0 5px #ccc";
  document.body.appendChild(osccred);
}

defineProperty();

//allow only time in eventtime from and to
addEventFrom.addEventListener("input", (e) => {
  addEventFrom.value = addEventFrom.value.replace(/[^0-9:]/g, "");
  if (addEventFrom.value.length === 2) {
    addEventFrom.value += ":";
  }
  if (addEventFrom.value.length > 5) {
    addEventFrom.value = addEventFrom.value.slice(0, 5);
  }
});

addEventTo.addEventListener("input", (e) => {
  addEventTo.value = addEventTo.value.replace(/[^0-9:]/g, "");
  if (addEventTo.value.length === 2) {
    addEventTo.value += ":";
  }
  if (addEventTo.value.length > 5) {
    addEventTo.value = addEventTo.value.slice(0, 5);
  }
});

function compareTimes(hours1,minutes1,hours2,minutes2) {

  if (hours1 < hours2) {
    return -1;
  } else if (hours1 > hours2) {
    return 1;
  } else {
    // Les heures sont égales, comparer les minutes
    if (minutes1 < minutes2) {
      return -1;
    } else if (minutes1 > minutes2) {
      return 1;
    } else {
      // Les minutes sont également égales
      return 0;
    }
  }
}


//function to add event to eventsArr
addEventSubmit.addEventListener("click", () => {
  const eventTitle = addEventTitle.value;
  const eventTimeFrom = addEventFrom.value;
  const eventTimeTo = addEventTo.value;
  const eventDescription = addEventDescription.value; // Ajout de la description
  const eventPlace = addEventPlace.value; // Ajout de la position
  if (eventTitle === "" || eventTimeFrom === "" || eventTimeTo === "") {
    alert("Please fill all the fields");
    return;
  }

  //check correct time format 24 hour
  const timeFromArr = eventTimeFrom.split(":");
  const timeToArr = eventTimeTo.split(":");
  if (
    timeFromArr.length !== 2 ||
    timeToArr.length !== 2 ||
    timeFromArr[0] > 23 ||
    timeFromArr[1] > 59 ||
    timeToArr[0] > 23 ||
    timeToArr[1] > 59 ||
    compareTimes(timeFromArr[0], timeFromArr[1],timeToArr[0], timeToArr[1]) != -1
  ) {
    alert("Format d'heure invalide");
    return;
  }

  const timeFrom = convertTime(eventTimeFrom);
  const timeTo = convertTime(eventTimeTo);

  //check if event is already added
  let eventExist = false;
  eventsArr.forEach((event) => {
    if (
      event.day === activeDay &&
      event.month === month + 1 &&
      event.year === year
    ) {
      event.events.forEach((event) => {
        if (event.title === eventTitle) {
          eventExist = true;
        }
      });
    }
  });
  if (eventExist) {
    alert("Evenement déjà existant");
    return;
  }
  const newEvent = {
    title: eventTitle,
    time: timeFrom + " - " + timeTo,
    description: eventDescription,
    place: eventPlace,
  };
  console.log(newEvent);
  console.log(activeDay);
  let eventAdded = false;
  if (eventsArr.length > 0) {
    eventsArr.forEach((item) => {
      if (
        item.day === activeDay &&
        item.month === month + 1 &&
        item.year === year
      ) {
        item.events.push(newEvent);
        eventAdded = true;
      }
    });
  }

  if (!eventAdded) {
    eventsArr.push({
      day: activeDay,
      month: month + 1,
      year: year,
      events: [newEvent],
    });
  }

  console.log(eventsArr);
  addEventWrapper.classList.remove("active");
  addEventTitle.value = "";
  addEventFrom.value = "";
  addEventTo.value = "";
  addEventDescription.value = "";
  addEventPlace.value = "";
  updateEvents(activeDay);
  //select active day and add event class if not added
  const activeDayEl = document.querySelector(".day.active");
  if (!activeDayEl.classList.contains("event")) {
    activeDayEl.classList.add("event");
  }
});



// Écoute les clics sur le conteneur des événements
eventsContainer.addEventListener("click", (e) => {
  // Vérifie si l'élément cliqué est un événement
  if (e.target.classList.contains("event")) {
    // Évite la propagation du clic sur le bouton toggleSUPP
    e.stopPropagation();

    // Récupère les informations sur l'événement
    const eventTitle = e.target.children[0].children[1].innerHTML;
    const eventTime = e.target.children[1].children[0].innerHTML;
    const eventDescription = e.target.children[2].children[0].innerHTML;
    const eventPlace = e.target.children[3].children[0].innerHTML;
    
    // Met à jour les éléments dans la div "information-evenement" avec les informations de l'événement
    document.getElementById("eventTitle").innerHTML = "<strong>Titre: </strong>" + eventTitle;
    document.getElementById("eventTime").innerHTML = "<strong>Heure: </strong>" + eventTime;
    document.getElementById("eventDescription").innerHTML = "<strong>Description: </strong>" + eventDescription;
    document.getElementById("eventPlace").innerHTML = "<strong>Position: </strong>" + eventPlace;


    // Écoute les clics sur le bouton toggleSUPP
    document.getElementById("toggleSUPP").addEventListener("click", () => {
      // Affiche une boîte de dialogue pour confirmer la suppression de l'événement
      if (confirm("Êtes-vous sûr de vouloir supprimer cet événement?")) {
        // Récupère le titre de l'événement à supprimer
        const eventTitle = e.target.children[0].children[1].innerHTML;
        // Parcourt le tableau des événements
        eventsArr.forEach((event) => {
          // Vérifie si l'événement appartient au jour actif
          if (
            event.day === activeDay &&
            event.month === month + 1 &&
            event.year === year
          ) {
            // Parcourt les événements du jour actif
            event.events.forEach((item, index) => {
              // Vérifie si le titre de l'événement correspond
              if (item.title === eventTitle) {
                // Supprime l'événement du tableau des événements
                event.events.splice(index, 1);
              }
            });
            // Si aucun événement n'est restant dans le jour, le supprimer du tableau des événements
            if (event.events.length === 0) {
              eventsArr.splice(eventsArr.indexOf(event), 1);
              // Supprime la classe "event" du jour s'il n'y a plus d'événements
              const activeDayEl = document.querySelector(".day.active");
              if (activeDayEl.classList.contains("event")) {
                activeDayEl.classList.remove("event");
              }
            }
          }
        });
        // Met à jour les événements affichés
        updateEvents(activeDay);

        // Cache la boîte de dialogue des informations sur l'événement
      var nav = document.querySelector('.information-evenement');
      nav.style.display = 'none';
      }
      else {
        // Annule toute l'opération si l'utilisateur a cliqué sur "Annuler"
        return;
      }
    });
  }
});




// Fonction pour afficher les détails de l'événement
function showEventDetails(eventTitle, eventTime) {
  // Vous pouvez modifier cette partie pour afficher les détails de l'événement
  var nav = document.querySelector('.information-evenement');
  nav.style.display = nav.style.display === 'none' ? 'flex' : 'none';
  events.stopPropagation(); // Empêche la propagation de l'événement de clic pour éviter la fermeture immédiate de la boîte de dialogue
}


// Écoute les clics sur l'ensemble du document
document.addEventListener('click', function(event) {
  var nav = document.querySelector('.information-evenement');
  // Vérifie si l'élément cliqué se trouve à l'intérieur de la boîte de dialogue
  if (!nav.contains(event.target)) {
      // Si l'élément cliqué est en dehors de la boîte de dialogue, masquez la boîte de dialogue
      nav.style.display = 'none';
  }
});



// Ajoutez un écouteur d'événements pour les clics sur les événements
eventsContainer.addEventListener("click", (e) => {
    if (e.target.classList.contains("event")) {
        const eventTitle = e.target.children[0].children[1].innerHTML;// récupère les informations sur les titres
        const eventTime = e.target.children[1].children[0].innerHTML;// récupère les informations sur les heures
        const eventDescription = e.target.children[2].children[0].innerHTML; // Récupère la description de l'événement
        const eventPlace = e.target.children[3].children[0].innerHTML; // Récupère la position de l'événement

        // Appelez la fonction pour afficher les détails de l'événement
        showEventDetails(eventTitle, eventTime, eventDescription, eventPlace);
    }
});


//function to get events from local storage
function getEvents() {
  fetch('get_events.php', {
    method: 'POST',
    //credentials: 'same-origin' // Ajoutez cette ligne si votre site utilise des cookies de session
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Erreur de réponse du serveur');
    }
    return response.json();
  })
  .then(data => {
    eventsArr.push(...data);
    initCalendar(); // Mettre à jour le calendrier une fois les événements récupérés
  })
  .catch(error => {
    console.error('Erreur lors de la récupération des événements:', error);
  });
}

function saveEvents() {
  console.log('Données transmises :', JSON.stringify(eventsArr));

  fetch('create_event.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(eventsArr),
  })
  .then(response => {
    console.log('Réponse du serveur :', response); // Ajoutez cette ligne pour déboguer
    if (!response.ok) {
      throw new Error('Erreur de réponse du réseau');
    }
    return response.json();
  })
  .then(data => {
    console.log('Événements enregistrés:', data);
  })
  .catch(error => {
    console.error('Erreur lors de l\'enregistrement des événements:', error);
    console.log('Erreur dans le JSON envoyé:', JSON.stringify(eventsArr));
  });
}







function convertTime(time) {
  const timeArr = time.split(":");
  const hours = parseInt(timeArr[0]);
  const minutes = parseInt(timeArr[1]);
  const timeObj = new Date();
  timeObj.setHours(hours, minutes);
  const options = { hour: 'numeric', minute: '2-digit', hour12: false, timeZone: 'Europe/Paris' };
  const formattedTime = timeObj.toLocaleTimeString('fr-FR', options);
  return formattedTime.replace(":", "h"); // Remplacer ":" par "h"
}



document.getElementById('toggleCount').addEventListener('click', function() {
  var nav = document.querySelector('.sidebar');
  nav.style.display = nav.style.display === 'none' ? 'flex' : 'none';
});

document.getElementById('toggleNav').addEventListener('click', function() {
    var nav = document.querySelector('.navigation');
    nav.style.display = nav.style.display === 'none' ? 'flex' : 'none';
});

document.getElementById('toggleADD').addEventListener('click', function() {
    var nav = document.querySelector('.new-agenda');
    nav.style.display = nav.style.display === 'none' ? 'flex' : 'none';
    event.stopPropagation(); // Empêche la propagation de l'événement de clic pour éviter la fermeture immédiate de la boîte de dialogue
});

// Écoute les clics sur l'ensemble du document
document.addEventListener('click', function(event) {
    var nav = document.querySelector('.new-agenda');
    // Vérifie si l'élément cliqué se trouve à l'intérieur de la boîte de dialogue
    if (!nav.contains(event.target)) {
        // Si l'élément cliqué est en dehors de la boîte de dialogue, masquez la boîte de dialogue
        nav.style.display = 'none';
    }
});



// Ajouter un écouteur d'événements pour détecter le changement de sélection plus tard faudra faire une fonction qui charge les données de l'agenda en question
selectElement.addEventListener("change", function() {
    // Récupérer l'option sélectionnée
    const selectedOption = this.options[this.selectedIndex];
    // Récupérer l'URL associée à l'option
    const href = selectedOption.getAttribute("href");
    // Rediriger vers l'URL associée
    window.location.href = href;
});

//username
