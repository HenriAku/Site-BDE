-- Suppression des tables existantes si elles existent
DROP TABLE IF EXISTS Achete CASCADE;
DROP TABLE IF EXISTS Detail_panier CASCADE;
DROP TABLE IF EXISTS Commente CASCADE;
DROP TABLE IF EXISTS Consulte CASCADE;
DROP TABLE IF EXISTS Contient_evenement CASCADE;
DROP TABLE IF EXISTS Contient_produit CASCADE;


DROP TABLE IF EXISTS Produit CASCADE;
DROP TABLE IF EXISTS Article CASCADE;
DROP TABLE IF EXISTS Fichier CASCADE;
DROP TABLE IF EXISTS Evenement CASCADE;
DROP TABLE IF EXISTS Adherent CASCADE;


--CREATION DES TABLES 
-- Création de la table Produit
CREATE TABLE Produit(
                          n_prod SERIAL PRIMARY KEY,
                          libelle_prod VARCHAR(255) NOT NULL,
                          stock_prod     INTEGER,
                          categorie_prod VARCHAR(255) NOT NULL CHECK (categorie_prod IN ('Vetement', 'Accessoire', 'Consommable')),
                          prix_prod      INTEGER NOT NULL,
                          description_prod TEXT NOT NULL,
                          couleur_prod  VARCHAR(255) NOT NULL,
                          taille_prod VARCHAR(255) NOT NULL CHECK (taille_prod IN ('XS', 'S', 'M', 'L', 'XL', 'XXL'))
);

-- Création de la table 'Article'
CREATE TABLE Article (
                         n_art SERIAL PRIMARY KEY,
                         titre_art VARCHAR(255) NOT NULL,
                         contenu_art TEXT NOT NULL, 
                         date_publi_art TIMESTAMP DEFAULT NOW()
);

-- Création de la table 'Evenement'
CREATE TABLE Evenement (
                         n_event SERIAL PRIMARY KEY,
                         nom_event VARCHAR(255) NOT NULL,
                         date_debut_event TIMESTAMP DEFAULT NOW(),
                         description_event TEXT NOT NULL,
                         adr_event VARCHAR(255) NOT NULL,
                         prix_event INTEGER NOT NULL                 
);

-- Création de la table 'Adherent'
CREATE TABLE Adherent (
                         n_etu SERIAL PRIMARY KEY,
                         num_etu VARCHAR(255) NOT NULL,
                         nom_etu VARCHAR(255) NOT NULL,
                         prenom_etu VARCHAR(255) NOT NULL,
                         num_etu VARCHAR(255) NOT NULL,
                         admin BOOLEAN DEFAULT false,
                         mdp_etu VARCHAR(255) NOT NULL,
                         mail_etu VARCHAR(255) NOT NULL UNIQUE
);

-- Création de la table 'Fichier'
CREATE TABLE Fichier (
                         nom_image VARCHAR(255) PRIMARY KEY
);

--CREATION DES RELATIONS 
-- Création de la relation 'Achete'
CREATE TABLE Achete (
                         n_vente SERIAL PRIMARY KEY,
                         n_prod INT NOT NULL,
                         n_etu INT NOT NULL,
                         quantite_vente INTEGER NOT NULL,
                         date_vente TIMESTAMP DEFAULT NOW(),
                         FOREIGN KEY (n_prod) REFERENCES Produit(n_prod) ON DELETE CASCADE,
                         FOREIGN KEY (n_etu) REFERENCES Adherent(n_etu) ON DELETE CASCADE
);

-- Création de la relation 'Achete'
CREATE TABLE Detail_panier (
                         n_dp SERIAL PRIMARY KEY,
                         n_prod INT NOT NULL,
                         n_etu INT NOT NULL,
                         quantite_dp INTEGER NOT NULL CHECK (quantite_dp > 0),
                         FOREIGN KEY (n_prod) REFERENCES Produit(n_prod) ON DELETE CASCADE,
                         FOREIGN KEY (n_etu) REFERENCES Adherent(n_etu) ON DELETE CASCADE
);

-- Création de la relation 'Commente'
CREATE TABLE Commente (
                         n_event INT NOT NULL,
                         n_etu INT NOT NULL,
                         note INTEGER NOT NULL CHECK (note BETWEEN 0 AND 5),
                         avis VARCHAR(255) NOT NULL,
                         PRIMARY KEY (n_event, n_etu),
                         FOREIGN KEY (n_event) REFERENCES Evenement(n_event) ON DELETE CASCADE,
                         FOREIGN KEY (n_etu) REFERENCES Adherent(n_etu) ON DELETE CASCADE
);

-- Création de la relation 'contient_produit'
CREATE TABLE contient_produit (
                         nom_image VARCHAR(255) NOT NULL,
                         n_prod INT NOT NULL,
                         PRIMARY KEY (nom_image, n_prod),
                         FOREIGN KEY (n_prod) REFERENCES Produit(n_prod) ON DELETE CASCADE,
                         FOREIGN KEY (nom_image) REFERENCES Fichier(nom_image) ON DELETE CASCADE
);

-- Création de la relation 'contient_evenement'
CREATE TABLE contient_evenement (
                         nom_image VARCHAR(255) NOT NULL,
                         n_event INT NOT NULL,
                         PRIMARY KEY (nom_image, n_event),
                         FOREIGN KEY (n_event) REFERENCES Evenement(n_event) ON DELETE CASCADE,
                         FOREIGN KEY (nom_image) REFERENCES Fichier(nom_image) ON DELETE CASCADE
);

-- Création de la relation 'Consulte'
CREATE TABLE Consulte (
                         n_art INT NOT NULL,
                         n_event INT NOT NULL,
                         n_etu INT NOT NULL,
                         PRIMARY KEY (n_art, n_etu, n_event),
                         FOREIGN KEY (n_event) REFERENCES Evenement(n_event) ON DELETE CASCADE,
                         FOREIGN KEY (n_etu) REFERENCES Adherent(n_etu) ON DELETE CASCADE,
                         FOREIGN KEY (n_art) REFERENCES Article(n_art) ON DELETE CASCADE
);

INSERT INTO Produit VALUES (0,'Lorem Ipsum1', 10, 'Vetement', 25
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','#FF0000', 'L');
INSERT INTO Produit VALUES (1,'Lorem Ipsum2', 5, 'Accessoire', 50
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','#FF0000', 'XL');
INSERT INTO Produit VALUES (2,'Lorem Ipsum3', 30, 'Consommable', 10
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','#FF0000', 'XS');
INSERT INTO Produit VALUES (3,'Lorem Ipsum4', 45, 'Vetement', 16
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','#FF0000', 'M');
INSERT INTO Produit VALUES (4,'Lorem Ipsum5', 45, 'Accessoire', 21
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentumtitle. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','#FF0000', 'L');

INSERT INTO Evenement VALUES (0,'Lorem Ipsum1', '2028-07-17 03:55:10'
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','29 rue Porte d Orange', 10);

INSERT INTO Evenement VALUES (1,'Lorem Ipsum2', '2000-02-19 17:08:22'
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','92 rue Saint Germain', 0);
INSERT INTO Evenement VALUES (2,'Lorem Ipsum3', '2002-09-25 20:49:10'
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','53 rue Reine Elisabeth', 5);
INSERT INTO Evenement VALUES (3,'Lorem Ipsum4', '2029-01-08 22:13:33'
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','23 Chemin des Bateliers', 0);
INSERT INTO Evenement VALUES (4,'Lorem Ipsum5', '2028-12-14 16:42:43'
, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','37 Place Charles de Gaulle', 3);

INSERT INTO Article VALUES (1,'Lorem Ipsum5', 
'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','2028-12-14 16:42:43');


INSERT INTO Article VALUES (2,'Lorem Ipsum5', 
 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','2028-12-14 16:42:43');

 INSERT INTO Article VALUES (3,'Lorem Ipsum5', 
 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
Nullam in neque in nisi elementum iaculis eget vel justo. Sed
 nec arcu ac urna interdum egestas at at lectus. Praesent iaculis 
 rutrum fermentum. Morbi iaculis gravida cursus. Suspendisse 
 elementum at ante nec sagittis. Integer et augue vel arcu malesuada 
 vestibulum nec nec ex. Nullam suscipit massa sem, id molestie velit rutrum a.','2028-12-14 16:42:43');
/*=
-- Insertion d'un utilisateur standard
INSERT INTO 'User' (firstname, lastname, email, password) VALUES
    ('John', 'Doe', 'john.doe@example.com', 'securePassword123');

-- Insertion d'un administrateur
INSERT INTO 'User' (firstname, lastname, email, password) VALUES
    ('root', 'toor', 'ro@ot.fr', 'adminPassword456');*/