const tabCarre = document.getElementsByClassName('carre-couleur');
const hiddenInput = document.getElementById('selectedColor');

const quantite = document.getElementById('quantite');
quantite.addEventListener('input', function () {
	const stock = parseInt(quantite.getAttribute('max'), 10);
	let value = parseInt(this.value, 10);

	if (value < 1) {
		this.value = 1;
	}

	if (value > stock) {
		this.value = stock;
	}
})

selectCouleur(0);

for (let index = 0; index < tabCarre.length; index++) {
	const element = tabCarre[index];
	console.log(element);
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
