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
DROP TABLE IF EXISTS "Adherent" CASCADE;


--CREATION DES TABLES 
-- Création de la table Produit
CREATE TABLE Produit(
                          n_prod SERIAL PRIMARY KEY,
                          libelle_prod VARCHAR(255) NOT NULL,
                          stock_prod     INTEGER,
                          categorie_prod VARCHAR(255) NOT NULL CHECK (categorie_prod IN ('Vetement', 'Accessoire', 'Consommable')),
                          prix_prod      INTEGER NOT NULL,
                          description_prod VARCHAR(255) NOT NULL,
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
                         nom_etu VARCHAR(255) NOT NULL,
                         prenom_etu VARCHAR(255) NOT NULL,
                         admin BOOLEAN DEFAULT false,
                         mdp_etu VARCHAR(255) NOT NULL,
                         mail_etu VARCHAR(255) NOT NULL UNIQUE
);

-- Création de la table 'Fichier'
CREATE TABLE Fichier (
                         nom_image VARCHAR(255) PRIMARY KEY,
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
                         FOREIGN KEY (n_etu) REFERENCES Evenement(n_etu) ON DELETE CASCADE,
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

/*
-- Insertion d'un utilisateur standard
INSERT INTO "User" (firstname, lastname, email, password) VALUES
    ('John', 'Doe', 'john.doe@example.com', 'securePassword123');

-- Insertion d'un administrateur
INSERT INTO "User" (firstname, lastname, email, password) VALUES
    ('root', 'toor', 'ro@ot.fr', 'adminPassword456');*/