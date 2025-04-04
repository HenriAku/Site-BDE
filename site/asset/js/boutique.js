document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-add-to-cart');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            const colors = this.getAttribute('data-colors').split(',');
            const firstColor = colors[0]; // Première couleur disponible
            const tailleSelect = document.getElementById(`LstTaille_${productId}`);
            const selectedTaille = tailleSelect.value;

            // Construire l'URL avec les paramètres
            const url = `/ajoutPanier.php?idProduit=${productId}&selectedColor=${encodeURIComponent(firstColor)}&ListeTaille=${selectedTaille}&quantite=1`;
			window.location.href = url;
        });
    });
});