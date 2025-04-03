document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('LstEvenements');
    const zoneDepotImageTxt = document.getElementById("zoneDepTxt");
    
    function updateEvent() {
        const selectedOption = select.options[select.selectedIndex];
        const eventId = selectedOption.value;
        
        // Mise à jour des champs du formulaire
        document.getElementById('eventId').value = eventId;
        document.getElementById('nom').value = selectedOption.dataset.nom || '';
        document.getElementById('date').value = selectedOption.dataset.date || '';
        document.getElementById('adresse').value = selectedOption.dataset.adresse || '';
        document.getElementById('description').value = selectedOption.dataset.description || '';
        document.getElementById('prix').value = selectedOption.dataset.prix || '';
        document.getElementById('places').value = selectedOption.dataset.places || '';
        
        // Gestion de l'image
        let imgFound = false;
        
        if (eventId) {
            // Recherche dans le tableau images fourni par Twig
            for (let image of images) {
                if (parseInt(image.n_event) === parseInt(eventId)) {  // Conversion en int pour comparaison
                    imgFound = true;
                    zoneDepotImageTxt.innerHTML = `
                        <img src="../asset/images/evenements/${image.nom_image}" 
                             style="max-width: 100%; max-height: 100%;"
                             alt="Image de l'événement">
                    `;
                    break;
                }
            }
        }
        
        if (!imgFound) {
            zoneDepotImageTxt.textContent = "Cliquez ici pour déposer une image";
        }
    }
    
    // Écouteur pour le changement de sélection
    select.addEventListener('change', updateEvent);
    
    // Initialisation
    if (select.options.length > 0) {
        updateEvent();
    }
});

function triggerFileInput() {
    document.getElementById('fileInput').click();
}

function changeImageNameZoneTxt() {
    const inputfile = document.getElementById('fileInput').files[0];
    const txtZoneDepot = document.getElementById('zoneDepTxt');
    
    if (inputfile) {
        if (inputfile.type.match('image.*')) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                txtZoneDepot.innerHTML = `
                    <img src="${e.target.result}" 
                         style="max-width: 100%; max-height: 100%;"
                         alt="Aperçu de l'image">
                    <div>${inputfile.name}</div>
                `;
            };
            
            reader.readAsDataURL(inputfile);
        } else {
            txtZoneDepot.innerHTML = `
                <div class="error">Le fichier doit être une image (PNG, JPG)</div>
            `;
        }
    } else {
        txtZoneDepot.textContent = "Cliquez ici pour déposer une image";
    }
}

document.getElementById('fileInput').addEventListener('change', changeImageNameZoneTxt);