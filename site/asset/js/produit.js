const lstProduit = document.getElementById("LstProduits");

lstProduit.addEventListener("change", () => {
	updateNom();
});

function updateNom() {
    const select = document.getElementById('LstProduits');
    const selectedProduit = select.options[select.selectedIndex].textContent;
    document.getElementById('nom').value = selectedProduit;
}

function changeImageNameZoneTxt() {
    const inputfile = document.getElementById('fileInput').files[0];
    const txtZoneDepot = document.getElementById('zoneDepTxt');
    
    if (inputfile) {
        // Vérifie si le fichier est une image
        if (inputfile.type.match('image.*')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Crée une image et la place dans la zone
                txtZoneDepot.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`;
            };
            
            reader.readAsDataURL(inputfile);
        } else {
            txtZoneDepot.textContent = "Le fichier doit être une image (PNG, JPG)";
        }
    } else {
        txtZoneDepot.textContent = "Cliquez ici pour déposer une image";
    }
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
                if (images[index]['n_prod'] == select.selectedIndex) {
                    imgNameChanged = true;
                    // Modification ici pour mieux afficher l'image
                    zoneDepotImageTxt.innerHTML = `<img src="../asset/images/produit/${images[index]['nom_image']}" style="max-width: 100%; max-height: 100%;">`;
                }
            }
            
            if (!imgNameChanged) {
                zoneDepotImageTxt.textContent = "Cliquez ici pour déposer une image";
            }
        
            const prix = document.getElementById("prix");
                
            if (select.selectedIndex-1 >= 0)
                prix.value = Produits[select.selectedIndex-1].price;
            else
                prix.value = 0;

            const textArea = document.getElementById("description");
            if (select.selectedIndex-1 >= 0)    
                textArea.value = Produits[select.selectedIndex-1].description;
            else
                textArea.value = "";

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


