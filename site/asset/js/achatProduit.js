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
}
