-- Suppression des tables existantes si elles existent
DROP TABLE IF EXISTS Achete CASCADE;
DROP TABLE IF EXISTS Detail_panier CASCADE;
DROP TABLE IF EXISTS Commente CASCADE;
DROP TABLE IF EXISTS Consulte CASCADE;
DROP TABLE IF EXISTS Contient_evenement CASCADE;
DROP TABLE IF EXISTS Contient_produit CASCADE;
DROP TABLE IF EXISTS Participe CASCADE;


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
                          categorie_prod VARCHAR(255) NOT NULL ,
                          prix_prod      INTEGER NOT NULL,
                          description_prod TEXT NOT NULL,
                          couleur_prod  VARCHAR(255) NOT NULL,
                          taille_prod VARCHAR(255) NOT NULL 
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
                         estValide boolean DEFAULT false,
                         nom_etu VARCHAR(255) NOT NULL,
                         prenom_etu VARCHAR(255) NOT NULL,
                         admin BOOLEAN DEFAULT false,
                         estConnecte BOOLEAN DEFAULT true,
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
                         estPayee boolean DEFAULT false, 
                         FOREIGN KEY (n_prod) REFERENCES Produit(n_prod) ON DELETE CASCADE,
                         FOREIGN KEY (n_etu) REFERENCES Adherent(n_etu) ON DELETE CASCADE
);

-- Création de la relation 'Achete'
CREATE TABLE Detail_panier (
                         n_dp SERIAL PRIMARY KEY,
                         n_prod INT NOT NULL,
                         n_etu INT NOT NULL,
                         taille_prod VARCHAR(255),
                         couleur_prod VARCHAR(255),
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

CREATE TABLE Participe (
                         n_etu INT NOT NULL,
                         n_event INT NOT NULL,
                         a_payer boolean DEFAULT false,
                         PRIMARY KEY (n_etu, n_event),
                         FOREIGN KEY (n_event) REFERENCES Evenement(n_event) ON DELETE CASCADE,
                         FOREIGN KEY (n_etu) REFERENCES Adherent(n_etu) ON DELETE CASCADE
);



-- Adhérents (inchangé)
INSERT INTO Adherent (num_etu, nom_etu,estValide, prenom_etu, admin, mdp_etu, mail_etu) VALUES
('230001', 'Dupont', true, 'Jean', true, '$2y$10$examplehash', 'jean.dupont@univ.fr'),
('230002', 'Martin',true, 'Sophie', false, '$2y$10$examplehash', 'sophie.martin@univ.fr'),
('230003', 'Bernard', false,'Pierre', false, '$2y$10$examplehash', 'pierre.bernard@univ.fr'),
('230004', 'Petit', false,'Marie', false, '$2y$10$examplehash', 'marie.petit@univ.fr'),
('230005', 'Durand',false, 'Luc', true, '$2y$10$examplehash', 'luc.durand@univ.fr');

-- Produits avec couleurs hexa et descriptions étendues
INSERT INTO Produit (libelle_prod, stock_prod, categorie_prod, prix_prod, description_prod, couleur_prod, taille_prod) VALUES
('T-shirt BDE Premium', 50, 'Vetement', 19, 
'Ce t-shirt en coton bio de haute qualité présente le logo du BDE brodé sur la poitrine. Matière douce et respirante, coupe unisexe. Lavage en machine à 30°C. Fabriqué en France dans le respect des normes environnementales.',
 '#000000, "#FF0000', 'M'),

('Sweat à capuche BDE', 30, 'Vetement', 45, 
'Sweat-shirt premium en coton/polyester avec capuche ajustable et poche kangourou. Logo sérigraphié haute résistance. Parfait pour les soirées fraîches ou le sport. Disponible en plusieurs tailles pour un confort optimal.',
 '#808080', 'L'),

('Gourde isotherme BDE', 100, 'Accessoire', 22, 
'Gourde isotherme en acier inoxydable 500ml (18/8) qui maintient vos boissons chaudes (12h) ou froides (24h). Design ergonomique avec bouchon étanche et revêtement extérieur résistant. Sans BPA, éco-responsable.',
 '#1E90FF', 'M'),

('Stylo BDE édition limitée', 200, 'Accessoire', 5, 
'Stylo bille ergonomique avec encre fluide et corps en métal recyclé. Gravure laser du logo BDE. Pointe moyenne 1.0mm pour une écriture douce. Idéal pour les prises de notes intensives.',
 '#FFD700', 'M'),

('Pack premium de stickers', 75, 'Consommable', 8, 
'Collection exclusive de 15 stickers haute qualité (vinyle durable, résistant aux UV et à l eau). Designs variés incluant le logo BDE, mascotte et slogans étudiants. Parfait pour personnaliser ordinateurs, gourdes et casiers.',
 '#FFFFFF', 'XS');

-- Evénements avec descriptions détaillées
INSERT INTO Evenement (nom_event, date_debut_event, description_event, adr_event, prix_event) VALUES
('Soirée de rentrée - Édition spéciale', '2023-09-15 20:00:00', 
'La traditionnelle soirée de rentrée revient avec une édition spéciale ! Au programme : DJ set, buffet gourmand, animations surprises et tombola avec des lots exceptionnels (bons d achat, places de concert...). Tenue élégante requise. Réservation obligatoire via le site du BDE. Ouverture des portes à 20h, prévoir pièce d identité.',
 'Bar étudiant "Le Cactus", Campus Principal', 10),

('Tournoi eSport Inter-Universitaire', '2023-10-10 14:00:00', 
'Pour la première fois, le BDE organise un tournoi inter-universités sur les jeux les plus populaires (League of Legends, Rocket League, Valorant). Équipes de 5 joueurs, cash prize pour les gagnants, streaming live avec commentateurs professionnels. Matériel fourni sur place mais vous pouvez amener votre propre setup. Inscriptions par équipe uniquement.',
 'Espace gaming, Bâtiment A, Salle 210', 0),

('Atelier CV/LinkedIn + Simulations entretiens', '2023-11-05 10:00:00', 
'Workshop intensif animé par des professionnels des RH pour booster votre employabilité. 1ère partie : rédaction de CV percutant et optimisation de profil LinkedIn. 2ème partie : simulations d entretiens avec feedback personnalisé. Apportez votre CV actuel. Places limitées à 30 participants.',
 'Espace carrières, Bâtiment B, Amphi 3', 0),

('Week-end ski Alpes d Huez', '2024-01-20 08:00:00', 
'Séjour tout compris de 3 jours/2 nuits dans la station mythique des Alpes d Huez. Hébergement en résidence étudiante (chambres de 4), forfait ski inclus, soirée en refuge et transport en bus privé. Équipement non fourni (location possible sur place). Pré-inscription requise avec acompte de 50€.',
 'Station des Alpes d Huez, RDV parking université', 199),

('Gala annuel - Soirée des Étoiles', '2024-05-25 19:30:00', 
'Événement phare de l année universitaire ! Soirée de gala prestigieuse avec dîner gastronomique, remise des prix, orchestre live et feu d artifice. Tenue de soirée obligatoire (costume/robe). Photographe professionnel présent. Réservation nominative avec ticket nominatif. Interdit aux moins de 18 ans.',
 'Palais des Congrès, 25 Avenue des Universités', 45);

-- Articles avec contenu complet
INSERT INTO Article (titre_art, contenu_art, date_publi_art) VALUES
('Bienvenue sur notre nouvelle plateforme BDE !', 
'Chers membres, après plusieurs mois de travail, nous sommes fiers de vous présenter la nouvelle plateforme du BDE ! Au programme :
- Interface intuitive et responsive
- Boutique en ligne sécurisée
- Calendrier interactif des événements
- Espace membre personnalisé
- Système de réservation en temps réel

N hésitez pas à nous faire part de vos retours via l onglet "Contact". Une version mobile Android/iOS arrivera en décembre 2023.

L équipe BDE',
 '2023-09-01 09:00:00'),

('Retour sur le tournoi sportif inter-filières', 
'Le tournoi annuel a rassemblé pas moins de 28 équipes représentant toutes les filières de l université. Après des matchs acharnés, voici le podium :

1. Équipe "Les Durs à Cuire" (Sciences Po)
2. Team "Les Matheux Fous" (Mathématiques)
3. Groupe "BioForce" (Biologie)

Félicitations à tous les participants ! Les photos de l événement sont disponibles dans la galerie. Merci à nos partenaires SportCampus et GoMuscu pour les lots offerts.

Rendez-vous l année prochaine pour une édition encore plus folle !',
 '2023-10-20 18:30:00'),

('Nouveautés boutique : collection hiver + soldes', 
'La boutique BDE s enrichit pour l hiver avec :

1. Nouveaux sweats colorés (limited edition)
2. Écharpes et bonnets assortis
3. Coffrets cadeaux de Noël
4. Mugs chauffants USB

Profitez de nos soldes d automne jusqu au 30 novembre :
- 20% sur toute la collection été
- 2 achetés = le 3ème à -50%
- Lots surprises pour tout achat >30€

Livraison express disponible pour les commandes avant le 15 décembre.',
 '2023-11-15 11:15:00');
 -- Articles supplémentaires
INSERT INTO Article (titre_art, contenu_art, date_publi_art) VALUES
('Interview exclusive : Notre président du BDE se confie', 
'À mi-mandat, nous avons rencontré Alexandre Durand, président du BDE, pour un bilan d étape.

**Q : Quel bilan tirez-vous de ces 6 mois ?**
"Extrêmement positif ! Nous avons déjà organisé 12 événements majeurs avec une participation en hausse de 30%. La nouvelle plateforme a été adoptée par 80% des membres."

**Q : Des projets à venir ?**
"Oui ! Nous préparons un voyage culturel à Barcelone en mars, et un partenariat avec le festival étudiant de musique. Sans oublier notre grande collecte de Noël pour les Restos du Cœur."

**Q : Un message pour les étudiants ?**
"Votre BDE vit grâce à vous. N hésitez pas à proposer vos idées - notre boîte à suggestions est ouverte à tous !"

Retrouvez l interview vidéo complète sur notre chaîne YouTube.',
 '2023-12-05 14:20:00'),

('Guide pratique : Réussir ses partiels sans stress', 
'À l approche des examens, voici nos conseils éprouvés :

**1. Organisation**
- Utilisez notre planning personnalisable (disponible en téléchargement)
- Technique Pomodoro : 25min de travail / 5min de pause
- Listes de priorités ABC (A=urgent, B=important, C=secondaire)

**2. Ressources utiles**
- Groupes de travail organisés par le BDE (horaires en ligne)
- Fiches de révision collaboratives
- Accès privilégié à la salle de silence (ouvert 24/7 pendant les exams)

**3. Bien-être**
- Ateliers relaxation les mardis soirs
- Pack "survie exam" disponible à la boutique (café bio, snacks healthy)
- Conseil médical gratuit sur rendez-vous

Bonus : Venez récupérer votre kit "anti-stress" (comprimés de vitamines, earplugs) au local BDE !',
 '2024-01-10 08:45:00'),

('Retour en images sur le Gala 2024', 
'Notre soirée des Étoiles a tenu toutes ses promesses ! 

**En chiffres :**
- 350 participants
- 12 heures de préparation
- 1 buffet d exception (merci au traiteur "Saveurs Campus")
- 3 groupes musicaux
- 1 feu d artifice spectaculaire

**Témoignages :**
"La meilleure soirée de l année !" - Clara, L2 Droit
"Une organisation impeccable" - Prof. Martin

Découvrez :
- La galerie photo complète
- Le making-of vidéo
- Le concours de la meilleure tenue (vote en ligne)

Merci à tous d avoir fait de cette soirée un moment inoubliable !',
 '2024-05-28 11:30:00');

-- Fichiers (images)
INSERT INTO Fichier (nom_image) VALUES
('tshirt_bde.jpg'),
('sweat_bde.jpg'),
('bob_bde.jpg'),
('casquette_bde.jpg'),
('pull_linux.jpg'),
('soiree_rentree.jpg'),
('default-event.jpg'),
('tournoi_jeux.jpg');

-- Relations contient_produit
INSERT INTO contient_produit (nom_image, n_prod) VALUES
('tshirt_bde.jpg', 1),
('sweat_bde.jpg', 2),
('bob_bde.jpg', 3),
('pull_linux.jpg', 4),
('casquette_bde.jpg', 5);

-- Relations contient_evenement
INSERT INTO contient_evenement (nom_image, n_event) VALUES
('soiree_rentree.jpg', 1),
('tournoi_jeux.jpg', 2),
('default-event.jpg', 3),
('default-event.jpg', 4),
('default-event.jpg', 5);

-- Commentaires
INSERT INTO Commente (n_event, n_etu, note, avis) VALUES
(1, 2, 4, 'Super soirée, ambiance géniale !'),
(2, 4, 3, 'Bien mais manquait de diversité de jeux');

-- Commentaires supplémentaires pour les événements
INSERT INTO Commente (n_event, n_etu, note, avis) VALUES
-- Soirée de rentrée (event 1)
(1, 1, 4, 'Super ambiance mais un peu trop bondé à mon goût'),
(1, 3, 5, 'Meilleure soirée depuis mon arrivée à la fac ! DJ incroyable'),
(1, 4, 3, 'Bonne organisation mais choix de musique moyen'),

-- Tournoi jeux vidéo (event 2)
(2, 2, 5, 'Parfaitement organisé avec du bon matériel'),
(2, 5, 4, 'Génial mais durée un peu trop courte'),

-- Atelier CV (event 3)
(3, 1, 5, 'Intervenant très professionnel, j ai appris énormément'),
(3, 2, 5, 'Atelier qui change la vie ! Mon CV a déjà retenu l attention des recruteurs'),
(3, 4, 4, 'Très utile mais aurait mérité plus d exercices pratiques'),

-- Week-end ski (event 4)
(4, 3, 5, 'Séjour parfait, moniteur de ski top et hébergement confortable'),
(4, 5, 2, 'Problème d organisation pour les remontées mécaniques'),

-- Gala de fin d année (event 5)
(5, 1, 5, 'Tout simplement magique, digne d un gala prestigieux'),
(5, 2, 4, 'Très belle soirée mais repas un peu léger'),
(5, 3, 5, 'Feu d artifique à couper le souffle !'),
(5, 4, 3, 'Cadre magnifique mais animation un peu faible en milieu de soirée');

-- Commentaires supplémentaires pour varier les notes
INSERT INTO Commente (n_event, n_etu, note, avis) VALUES
(1, 5, 2, 'Pas assez de places assises et trop bruyant'),
(2, 1, 3, 'Certains jeux manquaient à l appel'),
(3, 5, 5, 'Atelier qui m a décroché un stage !'),
(4, 2, 4, 'Très bon rapport qualité-prix pour un week-end ski'),
(5, 5, 1, 'Déçu par le DJ et le service en salle');

-- Achats
INSERT INTO Achete (n_prod, n_etu, quantite_vente) VALUES
(1, 2, 2),
(3, 3, 1),
(4, 4, 5);

-- Panier
INSERT INTO Detail_panier VALUES
(1, 2, 1, 'XL', '#45FA2C', 2),
(2, 3, 2, 'M', '#44FCDC', 1),
(3, 4, 3, 'XXL', '#71CA2C', 5); 

-- Consultations
INSERT INTO Consulte (n_art, n_event, n_etu) VALUES
(1, 1, 2),
(1, 1, 3),
(2, 2, 4);