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
