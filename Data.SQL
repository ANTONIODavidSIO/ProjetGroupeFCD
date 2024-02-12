SET NAMES utf8mb4; 

CREATE DATABASE wiki_scp; 

USE wiki_scp; 

CREATE TABLE Utilisateur (  

    idUtilisateur INT AUTO_INCREMENT,  
    nom VARCHAR(64),  
    prenom VARCHAR(64),  
    email VARCHAR(64),  
    admin TINYINT DEFAULT '0',
    idBan TINYINT DEFAULT '0',
    mot_de_passe VARCHAR(64),
    date_inscription DATE DEFAULT current_timestamp(),
    heure_inscription TIME DEFAULT current_timestamp(),
    avatar varchar(64) DEFAULT 'image/user.png',
    PRIMARY KEY (idUtilisateur)

);

CREATE TABLE Article ( 

    idArticle INT AUTO_INCREMENT,
    creation DATETIME DEFAULT current_timestamp(), 
    idCreateur INT,
    titre Text,
    contenu Text,
    visible TINYINT DEFAULT '1',
    PRIMARY KEY (idArticle),
    FOREIGN KEY (idCreateur) REFERENCES Utilisateur(idUtilisateur) 

); 

CREATE TABLE Modifications(

    idModification INT AUTO_INCREMENT,
    id_user INT,
    num_article INT,
    date_modif DATETIME,
    PRIMARY KEY (idModification),
    FOREIGN KEY (id_user) REFERENCES Utilisateur(idUtilisateur),
    FOREIGN KEY (num_article) REFERENCES Article(idArticle)
    
);

insert into Utilisateur (nom, prenom, email, mot_de_passe, admin, idBan) values
("admin", "admin", "admin@mailtester.com",  SHA2("insertadmin", 256), 1, 0),
("user", "user", "user@mailtester.com", SHA2("insertuser", 256), 0, 0),
("userban", "userban", "userban@mailtester.com", SHA2("insertbanuser", 256), 0, 1);

insert into Article (idCreateur, titre, contenu) values
(1, "voila un titre", "la c'est un contenu"),
(2, "titre de jean bombeur", "c'est mon contenu");