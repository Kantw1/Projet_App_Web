# Projet_App_Web
Creation d'une application Web


Dans un fichier il faudra qu'on stock toutes les données de l'utilisateur, nom, prenom, nom utilisateur, mdp, agenda auxquels il à accés avec leur lien

un fichier pour chaque agenda


également quand on clique sur un événement il faudrait réccupérer toutes les informations dessus pour les afficher dans l'onglet qui présente l'




### Chaque utilisateur a accès à un nombre défini d'agenda qui appartiennent à une base de donnée, et chaque agenda est une base de donnée
## //header('Location: Agenda.html'); dans Create_Agenda.php à enlever mais essayer de trouver comment rester sur la même pas sans la recharger

#changer la table sql events, ajouter une colonne avec le code de l'agenda




#SQL

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Table des agendas
CREATE TABLE agendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agenda_name VARCHAR(255) NOT NULL,
    agenda_code VARCHAR(10) NOT NULL
);

-- Table de liaison entre les utilisateurs et les agendas auxquels ils ont accès
CREATE TABLE user_agendas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    agenda_code TEXT
);

-- Table des événements
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agenda_id INT NOT NULL,
    day INT NOT NULL,
    month INT NOT NULL,
    year INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    time VARCHAR(20) NOT NULL,
    description TEXT,
    place VARCHAR(255),
    FOREIGN KEY (agenda_id) REFERENCES agendas(id)
);
