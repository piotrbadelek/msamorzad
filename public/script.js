"use strict";

function $(selector) {
	return document.querySelector(selector);
}

function store(name, value) {
	if (name && value === "") {
		localStorage.removeItem(name);
	} else if (name && value) {
		localStorage.setItem(name, value);
	} else if (name) {
		return localStorage.getItem(name);
	} else {
		localStorage.clear();
	}
}

function calculateTotalAmount() {
	$("#totalAmount").innerText = `Łącznie: ${$("#money").value * $("[data-total-students]").dataset.totalStudents} zł`
}

function enableNotifications() {
	initSW();
	const enableNotificationsButton = $("#enableNotifications");
	enableNotificationsButton.innerText = "Czekaj...";
	enableNotificationsButton.disabled = true;
	enableNotificationsButton.removeEventListener("click", enableNotifications);
}

function disableNotifications() {
	$("#notificationDialogue").open = false;
}

let installPrompt = null;
const installButton = document.querySelector("#installButton");
const installDialouge = document.querySelector("#installDialogue");

window.addEventListener("beforeinstallprompt", (event) => {
	event.preventDefault();
	installPrompt = event;
	installDialouge.open = true;
});

installButton.addEventListener("click", async () => {
	if (!installPrompt) {
		return;
	}
	const result = await installPrompt.prompt();
	console.log(`Install prompt was: ${result.outcome}`);
	disableInAppInstallPrompt();
});

function disableInAppInstallPrompt() {
	installPrompt = null;
	installDialouge.open = false;
}

function dontInstallApp() {
	installDialouge.open = false;
}


/* Notifications */
let swReady = navigator.serviceWorker.ready;

function initSW() {
	if (!"serviceWorker" in navigator) {
		//service worker isn't supported
		alert("Wystąpił błąd. Zaaktualizuj przeglądarkę aby włączyć powiadomienia.");
		return;
	}

	//don't use it here if you use service worker
	//for other stuff.
	if (!"PushManager" in window) {
		//push isn't supported
		alert("Wystąpił błąd. Zaaktualizuj przeglądarkę aby włączyć powiadomienia.");
		return;
	}

	//register the service worker
	navigator.serviceWorker.register('/sw.js')
		.then((reg) => {
			console.log('SW registered!', reg)
			initPush();
		})
		.catch(err => console.log(err));
}

document.addEventListener('DOMContentLoaded', function () {
	const requestNotificationPermission = async () => {
		const permission = await Notification.requestPermission();
		if (permission !== "granted") {
			$("#notificationDialogue").open = true;
		}
	}
	if (!$("[data-dont-show-notif-prompt]")) {
		requestNotificationPermission();
	}
});

function initPush() {
	if (!swReady) {
		return;
	}

	new Promise(function (resolve, reject) {
		const permissionResult = Notification.requestPermission(function (result) {
			resolve(result);
		});

		if (permissionResult) {
			permissionResult.then(resolve, reject);
		}
	})
		.then((permissionResult) => {
			if (permissionResult !== 'granted') {
				throw new Error('We weren\'t granted permission.');
			}
			subscribeUser();
		});
}

/**
 * Subscribe the user to push
 */
function subscribeUser() {
	swReady
		.then((registration) => {
			const subscribeOptions = {
				userVisibleOnly: true,
				applicationServerKey: urlBase64ToUint8Array(
					'BAV0QmjpLYEP2N0Eg9qMStk67qbhZVi6DhiCTaNcPBTZ_q6TFI0E0WtPuZq5-w5rKPJ-YHJPEyAbkxoCJECu6fo'
				)
			};

			return registration.pushManager.subscribe(subscribeOptions);
		})
		.then((pushSubscription) => {
			console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
			setTimeout(() => {
				storePushSubscription(pushSubscription);
			}, 500)
		});
}

/**
 * send PushSubscription to server with AJAX.
 * @param {object} pushSubscription
 */
function storePushSubscription(pushSubscription) {
	const token = document.querySelector('meta[name=csrf-token]').getAttribute('content');

	fetch('/push', {
		method: 'POST',
		body: JSON.stringify(pushSubscription),
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json',
			'X-CSRF-Token': token
		}
	})
		.then((res) => {
			return res.json();
		})
		.then((res) => {
			console.log(res);
			$("#notificationDialogue").open = false;
		})
		.catch((err) => {
			console.log(err)
		});
}

/**
 * urlBase64ToUint8Array
 *
 * @param {string} base64String a public vapid key
 */
function urlBase64ToUint8Array(base64String) {
	let padding = '='.repeat((4 - base64String.length % 4) % 4);
	let base64 = (base64String + padding)
		.replace(/\-/g, '+')
		.replace(/_/g, '/');

	let rawData = window.atob(base64);
	let outputArray = new Uint8Array(rawData.length);

	for (let i = 0; i < rawData.length; ++i) {
		outputArray[i] = rawData.charCodeAt(i);
	}
	return outputArray;
}
