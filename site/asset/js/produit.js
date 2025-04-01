const lstProduit = document.getElementById("LstProduits");
lstProduit.addEventListener("change", () => {
	updateNom();
});

function updateNom() {
	const selectedProduit = document.getElementById('LstProduits').value;
	
	document.getElementById('nom').value = selectedProduit;
}

document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('LstProduits');
    const colorBox = document.createElement('span');
    
    // Style du carré de couleur
    colorBox.style.display = 'inline-block';
    colorBox.style.width = '12px';
    colorBox.style.height = '12px';
    colorBox.style.marginLeft = '8px';
    colorBox.style.verticalAlign = 'middle';
    colorBox.style.border = '1px solid #ccc'; // Bordure optionnelle
    
    // Ajoute le carré après le select
    select.insertAdjacentElement('afterend', colorBox);
    
    // Fonction de mise à jour
    function updateProduit() {
        const selectedOption = select.options[select.selectedIndex];
        const color = selectedOption.dataset.color;
        
        if (color) {
            colorBox.style.backgroundColor = color;
            colorBox.style.visibility = 'visible';
        } else {
            // Cache le carré si pas de couleur (option vide)
            colorBox.style.visibility = 'hidden';
            colorBox.style.backgroundColor = 'transparent';
        }
        
		const zoneDepotImageTxt = document.getElementById("zoneDepTxt");
        let imgNameChanged = false;
		for (let index = 0; index < images.length; index++) {
			if (images[index]['n_prod'] == select.selectedIndex)
			{
				imgNameChanged = true;
				zoneDepotImageTxt.textContent = images[index]['nom_image'];
			}
		}
        

		if (! imgNameChanged){
			zoneDepotImageTxt.textContent = "Cliquez ici pour déposer une image";
		}

		const prix = document.getElementById("prix");;
        
        if (select.selectedIndex-1 >= 0)
		    prix.value = Produits[select.selectedIndex-1].price;
        else
            prix.value = 0;
    }
    
    // Écouteur d'événement
    select.addEventListener('change', updateProduit);
    
    // Initialisation
    updateProduit();

});
  
function triggerFileInput() {
    document.getElementById('fileInput').click();
}

document.getElementById('fileInput').addEventListener('change', () =>{
	changeImageNameZoneTxt();
})

function changeImageNameZoneTxt(){
	const inputfile    = document.getElementById('fileInput').files[0];
	const txtZoneDepot = document.getElementById('zoneDepTxt');

	txtZoneDepot.textContent = inputfile.name;
}


