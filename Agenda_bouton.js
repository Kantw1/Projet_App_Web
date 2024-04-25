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
  