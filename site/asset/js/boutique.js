for (let index = 1; index < 4; index++) {
	const btn = document.getElementById("btn"+index);
	btn.addEventListener("click", () => {
		switchBtn(index);
	});
}

function switchBtn(index){
	const btn = document.getElementById("btn"+index);
	
	if (btn.textContent.includes("▼"))
		btn.textContent = btn.textContent.replace("▼", "▲");
	else
		btn.textContent = btn.textContent.replace("▲", "▼");
}

function setImg(){
	const imagesProduits = document.getElementsByClassName("image-prod");
	for (let index = 0; index < imagesProduits.length; index++) {
		const imgProd = imagesProduits[index];
		try {
			imgProd.setAttribute("src", "../asset/images/produit/" + images[imgProd.id-1].nom_image);
		} catch (error) {}
	}
}

setImg();
