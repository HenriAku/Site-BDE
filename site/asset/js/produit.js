const lstProduit = document.getElementById("LstProduits");

lstProduit.addEventListener("change", () => {
    updateNom();
    updateProdId(); // Ajout de cette ligne
    grisserImage();
});

function grisserImage(){
    const inputImg = document.getElementById("fileInput");
    const zoneImg = document.getElementById("zoneImg");
    if (lstProduit.selectedIndex != 0){
        inputImg.disabled = true;
        zoneImg.style.backgroundColor = '#959595';
    }
    else{
        inputImg.disabled = false;
        zoneImg.style.backgroundColor = '#FFFFFF';
    }
}

function updateProdId() {
    const select = document.getElementById('LstProduits');
    const selectedOption = select.options[select.selectedIndex];
    document.getElementById('prodId').value = selectedOption.value;
}

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
    colorBox.style.border = '1px solid #ccc';
    
    select.insertAdjacentElement('afterend', colorBox);
    
    function updateProduit() {
        const selectedOption = select.options[select.selectedIndex];
        const color = selectedOption.dataset.color;
        
        // Mise à jour de l'ID du produit
        const prodIdInput = document.getElementById('prodId');
        prodIdInput.value = selectedOption.value;
        //console.log(Produits[select.selectedIndex].color);
        if (color) {
            colorBox.style.backgroundColor = color;
            colorBox.style.visibility = 'visible';
        } else {
            colorBox.style.visibility = 'hidden';
            colorBox.style.backgroundColor = 'transparent';
        }
        
        const zoneDepotImageTxt = document.getElementById("zoneDepTxt");
        let imgNameChanged = false;
        
        for (let index = 0; index < images.length; index++) {
            if (images[index]['n_prod'] == selectedOption.value) { // Modification ici pour utiliser la valeur plutôt que l'index
                imgNameChanged = true;
                zoneDepotImageTxt.innerHTML = `<img src="../asset/images/produit/${images[index]['nom_image']}" style="max-width: 100%; max-height: 100%;">`;
            }
        }
        
        if (!imgNameChanged) {
            zoneDepotImageTxt.textContent = "Cliquez ici pour déposer une image";
        }
    
        const prix = document.getElementById("prix");
        const textArea = document.getElementById("description");
        const stock = document.getElementById("stock");
        const categorie = document.getElementById("categorie");
        const taille = document.getElementById("taille");

        if (select.selectedIndex > 0){ // Modification de la condition
            prix.value = Produits[select.selectedIndex-1].price;
            textArea.value = Produits[select.selectedIndex-1].description;
            stock.value = Produits[select.selectedIndex-1].stock;
            categorie.value = Produits[select.selectedIndex-1].category;
            taille.value = Produits[select.selectedIndex-1].size;
        } 
        else {
            prix.value = 0;
            textArea.value = "";
            stock.value = 0;
            categorie.value = "";
            taille.value = "";
        }
    }
    
    select.addEventListener('change', updateProduit);
    updateProduit(); // Initialisation
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

document.getElementById('addcolor').addEventListener('click', (event) => {
    event.preventDefault(); 
    const div = document.getElementById("lstColor");

    div.innerHTML += '<input type="color" id="colorPicker" name="colorPicker[]" value="{{ colorPicker|default('+'#ff0000'+') }}"></input>';
})


