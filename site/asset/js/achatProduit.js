const tabCarre = document.getElementsByClassName('carre-couleur');
const hiddenInput = document.getElementById('selectedColor');

for (let index = 0; index < tabCarre.length; index++) {
	const element = tabCarre[index];
	element.addEventListener("click", () => {
		selectCouleur(index);
	});
}

function selectCouleur(index){
	for (let i = 0; i < tabCarre.length; i++) {
		const element = tabCarre[i];
		element.classList.remove('select');
	}

	const selectedElement = tabCarre[index];
    selectedElement.classList.add('select'); // Ajoute la classe 'select' au carré cliqué

    // Met à jour la valeur du champ caché avec la couleur sélectionnée
    hiddenInput.value = selectedElement.getAttribute('data-color');
    console.log("Couleur sélectionnée :", hiddenInput.value);
}

document.getElementById('productForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche la soumission normale du formulaire

    // Récupérer les données du formulaire
    const formData = new FormData(this);

	const submitButton = event.submitter;
    console.log("Bouton déclencheur :", submitButton);

	if (submitButton.getAttribute("id") == "btnAcheter"){
		const redir = document.getElementById("redirection");
		redir.value = "true";
	}

    // Envoyer les données via AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/ajoutPanier.php', true); // Fichier PHP qui traitera la requête
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert('Produit ajouté au panier avec succès !');
        } else {
            alert('Erreur lors de l\'ajout au panier.');
        }
    };
    xhr.send(formData); // Envoie les données au serveur
});
