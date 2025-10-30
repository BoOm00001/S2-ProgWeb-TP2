DROP TABLE IF EXISTS TDemande;
DROP TABLE IF EXISTS TEleve;
DROP TABLE IF EXISTS TCours;

CREATE TABLE TCours (
  numeroCours  CHAR(10) PRIMARY KEY,
  titre         VARCHAR(100),
  session       INT
);

INSERT INTO TCours (`numeroCours`, `titre`, `session`) VALUES 
  ('420-W10-SF', 'Introduction à la programmation', '1'),
  ('420-W12-SF', 'Systèmes d\'exploitation', '1'),
  ('201-423-SF', 'Mathématiques pour l\'informaticien 1', '1'),
  ('420-W14-SF', 'Perspectives professionnelles en TI', '1'),
  ('420-W15-SF', 'Programmation WEB 1', '1'),
  ('420-W20-SF', 'Programmation objet 1', '2'),
  ('420-W33-SF', 'Réseaux', '2'),
  ('201-424-SF', 'Mathématiques pour l\'informaticien II', '2'),
  ('420-W23-SF', 'Bases de données relationnelles', '2'),
  ('420-W24-SF', 'Programmation WEB II', '2'),
  ('420-W30-SF', 'Programmation objet II', '3'),
  ('420-W31-SF', 'Algorithmique avancée', '3'),
  ('420-W44-SF', 'Infrastructure technologique et virtualisation', '3'),
  ('420-W34-SF', 'Bases de données avancées ', '3'),
  ('420-W35-SF', 'Conception d\'applications et méthodes agiles', '3'),
  ('420-W45-SF', 'Installation des serveurs et des services', '4'),
  ('420-W46-SF', 'Applications WEB et bases de données', '4'),
  ('420-W47-SF', 'Développement de services d\'échange de données', '4'),
  ('420-W48-SF', 'Applications mobiles et objets connectés', '4'),
  ('420-W54-SF', 'Innovation et veille technologique', '5'),
  ('420-W55-SF', 'Enjeux en cybersécurité', '5'),
  ('360-W56-SF', 'Interactions professionnelles à l\'ère numérique', '5'),
  ('420-W57-SF', 'Projet synthèse en contexte TI diversifié', '5'),
  ('420-W62-SF', 'Stage - Interactions professionnelles', '6'),
  ('420-W63-SF', 'Stage - Développement en entreprise', '6');

CREATE TABLE TEleve (
  matricule       CHAR(7) PRIMARY KEY,
  prenom          VARCHAR(30) NOT NULL,
  nom             VARCHAR(30) NOT NULL,
  estTuteur       BOOL
);

INSERT INTO TEleve (matricule, prenom, nom, estTuteur) VALUES
   ('1531111', 'Adam',    'Bernard',   false),
   ('1532222', 'Charles', 'Demers',    false),
   ('1533333', 'Eric',    'Fillion',   false),
   ('1439999', 'Kim',     'Labrecque', false),
   ('1330000', 'Monique', 'Nolet',     false);


CREATE TABLE TDemande (
  numeroDemande       INT PRIMARY KEY AUTO_INCREMENT,
  matriculeTutore     CHAR(7) NOT NULL,
  numeroCours        CHAR(10) NOT NULL,
  descriptionDemande  TEXT NOT NULL,
  matriculeTuteur     CHAR(7),
  commentaireTuteur   TEXT
);

/* DONNÉES POUR TESTS */
INSERT INTO TDemande (matriculeTutore, numeroCours, descriptionDemande, matriculeTuteur, commentaireTuteur) VALUES
  ('1532222', '420-W24-SF', 'Comment fonctionne le foreach???', '1439999', 
   'Charles n\'a plus de difficulté avec cette fonction.  Il est en mesure de l\'utiliser pour accéder aux éléments individuels d\'une collection.');
INSERT INTO TDemande (matriculeTutore, numeroCours, descriptionDemande) VALUES
  ('1531111', '420-W24-SF', 'Je cherche à comprendre les routes paramétrées avec Laravel');
INSERT INTO TDemande (matriculeTutore, numeroCours, descriptionDemande, matriculeTuteur) VALUES
  ('1531111', '420-W24-SF', 'Comment passer des paramètres à une vue avec Laravel', '1439999');