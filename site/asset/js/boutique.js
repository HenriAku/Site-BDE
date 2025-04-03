document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.btn-add-to-cart');

    buttons.forEach(button => {
        button.addEventListener('click', function () {
            const productId = this.getAttribute('data-id');
            const colors = this.getAttribute('data-colors').split(',');
            const firstColor = colors[0]; // Première couleur disponible
            const tailleSelect = document.getElementById(`LstTaille_${productId}`);
            const selectedTaille = tailleSelect.value;

            // Préparer les données pour l'achat
            const formData = new FormData();
            formData.append('idProduit', productId);
            formData.append('ListeTaille', selectedTaille);
            formData.append('selectedColor', firstColor);
            formData.append('quantie', 1); // Quantité fixe à 1
            formData.append('redirection', false); // Pas de redirection

            // Envoyer la requête AJAX
            fetch('/ajoutPanier.php', {
                method: 'POST',
                body: formData
            })
			.then(response => response.json())
			.then(data => {
				
				if (data.success) {
					alert('Produit ajouté au panier avec succès !');
				} else {
					alert('Erreur : ' + data.message);
				}
			})
			.catch(error => {
				console.error('Erreur lors de l\'ajout au panier :', error);
				alert('Une erreur est survenue.');
			});
        });
    });
});