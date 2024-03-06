"use strict";

$("#beginOobe").addEventListener("click", () => {
	if (!isInstalled()) {
		$("#view0").classList.add("hidden");
		$("#view1").classList.remove("hidden");
		adaptInstallInstructions();
	} else {
		$("#view0").classList.add("hidden");
		$("#view2").classList.remove("hidden");
	}
});

let oobeInstallPrompt = null;

function isInstalled() {
	// For iOS
	if (window.navigator.standalone) return true

	// For Android
	if (window.matchMedia('(display-mode: standalone)').matches) return true

	// If neither is true, it's not installed
	return false
}

function adaptInstallInstructions() {
	if (supportsBeforeInstallPrompt) {
		// BeforeInstallPrompt is supported, continue along the best path
		$("#firefoxAndroidInstall").classList.add("hidden");
		$("#safariInstall").classList.add("hidden");

		$("#view1 [data-oobe='installButton']").addEventListener("click", async () => {
			if (!oobeInstallPrompt) {
				return;
			}
			const result = await oobeInstallPrompt.prompt();
			$("#view1").classList.add("hidden");
			$("#view2").classList.remove("hidden");
		});
	} else if (isAndroidFirefox) {
		$("#safariInstall").classList.add("hidden");
		$("#autoInstall").classList.add("hidden");
		$("#view1 img").src = "/img/oobe/install_firefox.webp";
	} else if (isiOSSafari) {
		$("#firefoxAndroidInstall").classList.add("hidden");
		$("#autoInstall").classList.add("hidden");
		$("#view1 img").src = "/img/oobe/install_ios.webp";
	} else {
		// PWA not supported
		$("#view1").classList.add("hidden");
		$("#view2").classList.remove("hidden");
	}

	document.querySelectorAll("#view1 [data-oobe='continueButton'], #view1 [data-oobe='notNowButton']").forEach(el => {
		el.addEventListener("click", () => {
			$("#view1").classList.add("hidden");
			$("#view2").classList.remove("hidden");
		});
	});
}

$("[data-oobe='enableNotifications']").addEventListener("click", () => {
	$("[data-oobe='enableNotifications']").innerText = "Czekaj...";
	$("[data-oobe='enableNotifications']").disabled = true;
	initPush(() => {
		$("#view2").classList.add("hidden");
		$("#view3").classList.remove("hidden");
	});
});

$("[data-oobe='disableNotifications']").addEventListener("click", () => {
	$("#view2").classList.add("hidden");
	$("#view3").classList.remove("hidden");
	disableNotifications();
});

window.addEventListener("beforeinstallprompt", (event) => {
	event.preventDefault();
	oobeInstallPrompt = event;
});
