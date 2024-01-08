"use strict";

const startCardInvoker = $("#startCardInvoker");
const resetCardInvoker = $("#resetCardInvoker");

function generateCard(isStarterCard) {
	if (startCardInvoker) {
		startCardInvoker.remove();
	} else {
		resetCardInvoker.remove();
	}
	const canvas = $("#cardCanvas");
	const ctx = canvas.getContext("2d");

	ctx.fillStyle = "white";
	ctx.fillRect(0, 0, 360, 240);

	ctx.font = "bold 48px Inter";
	ctx.fillStyle = "black";
	ctx.fillText("mSamorząd", 15, 60);

	const password = canvas.dataset["password"];
	const username = canvas.dataset["username"];
	const name = canvas.dataset["name"];
	ctx.font = "16px Inter";
	if (isStarterCard) {
		ctx.fillText(`Karta startowa dla: ${name}`, 15, 100);
	} else {
		ctx.fillText(`Karta zmiany hasła dla: ${name}`, 15, 100);
	}
	ctx.fillText(`Nazwa użytkownika: ${username}`, 15, 130);
	ctx.fillText(`Tymczasowe hasło: ${password}`, 15, 160);
	ctx.fillText(`Hasło zmienisz po zalogowaniu.`, 15, 190);
	ctx.font = "bold 16px Inter";
	ctx.fillText(`https://msamorzad.pl`, 15, 220);
	createCanvasDownloadLink(canvas);
}

function createCanvasDownloadLink(canvas) {
	const downloadLink = $("#downloadCard");
	downloadLink.hidden = false;
	downloadLink.setAttribute("download", "card.png");
	downloadLink.setAttribute("href", canvas.toDataURL("image/png").replace("image/png", "image/octet-stream"));
}

if (startCardInvoker) {
	startCardInvoker.addEventListener("click", () => {
		generateCard(true);
	});
} else if (resetCardInvoker) {
	resetCardInvoker.addEventListener("click", () => {
		generateCard(false);
	});
}
