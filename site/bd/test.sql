
/*
INSERT INTO contient_produit (nom_image, n_prod) VALUES
('tshirt_bde.jpg', 1),
('sweat_bde.jpg', 2),
('bob_bde.jpg', 3),
('casquette_bde.jpg', 4),
('bob_bde.jpg', 3);*/

/*
UPDATE Produit
SET couleur_prod = TRIM(TRAILING ',' FROM couleur_prod)
WHERE couleur_prod LIKE '%,';
*/

DELETE FROM Produit
WHERE n_prod NOT IN (1, 2, 3, 4, 5);