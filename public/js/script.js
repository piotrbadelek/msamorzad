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

const ua = window.navigator.userAgent;
const isiOS = !!ua.match(/iPad/i) || !!ua.match(/iPhone/i);
const isWebkit = !!ua.match(/WebKit/i);
const isiOSSafari = isiOS && isWebkit && !ua.match(/CriOS/i);
const isAndroid = ua.toLowerCase().indexOf("android") > -1;
const isFirefox = ua.toLowerCase().indexOf("firefox") > -1;
const isAndroidFirefox = isAndroid && isFirefox;
const supportsBeforeInstallPrompt = typeof BeforeInstallPromptEvent === "function";

const moneyInput = $("#money");

if (moneyInput) {
	moneyInput.addEventListener("input", calculateTotalAmount);
}

function calculateTotalAmount() {
	$("#totalAmount").innerText = `Łącznie: ${$("#money").value * $("[data-total-students]").dataset.totalStudents} zł`
}

const enableNotificationsButton = $("#enableNotifications");

function enableNotifications() {
	initSW();
	enableNotificationsButton.innerText = "Czekaj...";
	enableNotificationsButton.disabled = true;
	enableNotificationsButton.removeEventListener("click", enableNotifications);
}

enableNotificationsButton.addEventListener("click", enableNotifications);

function disableNotifications() {
	$("#notificationDialogue").open = false;
	store("notificationsDelayed", "true");
}

let installPrompt = null;
const installButton = document.querySelector("#installButton");
const installDialouge = document.querySelector("#installDialogue");

if (!$("[data-dont-show-notif-prompt]") && !store("installDelayed")) {
	$("#disableNotifications").addEventListener("click", disableNotifications);
	window.addEventListener("beforeinstallprompt", (event) => {
		event.preventDefault();
		installPrompt = event;
		installDialouge.open = true;
	});
}

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
	store("installDelayed", "true");
}

$("#dontInstallApp").addEventListener("click", dontInstallApp);


/* Notifications */
/* Fix for iOS Safari, which sometimes randomly just doesn't allow
	you to register service workers for whatever reason.
	Couldn't replicate behaviour, but it definitely is a thing
	https://msamorzad.sentry.io/issues/4837572428 */
let swReady = navigator.serviceWorker && navigator.serviceWorker.ready;

function initSW(callInitPush = true) {
	if (!swReady) {
		//service worker isn't supported
		return;
	}

	//don't use it here if you use service worker
	//for other stuff.
	if (!"PushManager" in window || !"Notification" in window) {
		//push isn't supported
		return;
	}

	//register the service worker
	navigator.serviceWorker.register('/sw.js')
		.then((reg) => {
			console.log('SW registered!', reg)

			if (callInitPush) {
				initPush();
			}
		})
		.catch(err => console.log(err));
}

document.addEventListener('DOMContentLoaded', function () {
	initSW(false);
	window.requestNotificationPermission = async () => {
		// Fix for iOS on versions before 16.4, as push notifications
		// are not supported on these versions.
		if (typeof Notification !== "undefined") {
			const permission = await Notification.requestPermission();
			if (permission !== "granted" && !store("notificationsDelayed")) {
				$("#notificationDialogue").open = true;
			} else if (permission === "granted") {
				initSW();
			}
		}
	}
	if (!$("[data-dont-show-notif-prompt]")) {
		requestNotificationPermission();
	}
});

function initPush(callback) {
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
		.then(permissionResult => {
			$("#notificationDialogue").open = false;
			subscribeUser(callback);
		})
		.catch(() => {
			alert("Nastąpił błąd. Zaaktualizuj przeglądarkę bądź ją zmień. Jeżeli korzystasz z systemu Android, skorzystaj z Firefoxa bądź Chrome, jeżeli korzystasz z iOSa, skorzystaj z Safari.");
		});
}

/**
 * Subscribe the user to push
 */
function subscribeUser(callback) {
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
				storePushSubscription(pushSubscription, callback);
			}, 500)
		});
}

/**
 * send PushSubscription to server with AJAX.
 * @param {object} pushSubscription
 * @param callback
 */
function storePushSubscription(pushSubscription, callback) {
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
			if (callback) {
				callback();
			}
			if (!callback) {
				$("#notificationDialogue").open = false;
			}
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

if (store("notificationsDelayed")) {
	if (Math.floor(Math.random() * 100) > 98) {
		store("notificationsDelayed", "");
	}
}

if (store("installDelayed")) {
	if (Math.floor(Math.random() * 100) > 88) {
		store("installDelayed", "");
	}
}

addEventListener("turbo:submit-start", ({target}) => {
	if (target.querySelector("button")) {
		target.querySelector("button").innerText = "Czekaj...";
		target.querySelector("button").disabled = true;
	}
})

const detectFeatures = function () {
	const searchInput = $("#messageSearch");
	if (searchInput) {
		searchInput.addEventListener("keyup", messageSearch);
	}

	function messageSearch() {
		const searchQuery = searchInput.value.toLowerCase();
		const messageContainers = document.querySelectorAll(".message_container");
		messageContainers.forEach(el => {
			el.hidden = !el.dataset["message"].toLowerCase().includes(searchQuery);
		});
	}

	const outdatedIosPrompt = $(".outdated-ios-info");

	if (outdatedIosPrompt && typeof Notification === "undefined" && isiOSSafari) {
		outdatedIosPrompt.style.display = "block";
	}

	const featureGuideExcludeStudentsInvoker = $("#feature-guide-exclude-students-invoker");
	if (featureGuideExcludeStudentsInvoker) {
		const featureGuideExcludeStudentsDialogue = $("#feature-guide-exclude-students");
		featureGuideExcludeStudentsInvoker.addEventListener("click", event => {
			event.preventDefault();
			featureGuideExcludeStudentsDialogue.open = true;
		});

		$("#feature-guide-exclude-students button").addEventListener("click", () => {
			featureGuideExcludeStudentsDialogue.open = false;
		});
	}

	const featureGuideBlikPaymentsInvoker = $("#blik-payments-guide-invoker");
	if (featureGuideBlikPaymentsInvoker) {
		const featureGuideBlikPaymentsDialogue = $("#feature-guide-blik-payments");
		featureGuideBlikPaymentsInvoker.addEventListener("click", event => {
			event.preventDefault();
			featureGuideBlikPaymentsDialogue.open = true;
		});

		$("#feature-guide-blik-payments button").addEventListener("click", () => {
			featureGuideBlikPaymentsDialogue.open = false;
		});
	}

	const excludeStudentsCheckbox = $("#excludeStudents");
	if (excludeStudentsCheckbox) {
		excludeStudentsCheckbox.addEventListener("click", () => {
			$("#excludedStudentsForm").hidden = !excludeStudentsCheckbox.checked;
		});
	}

	const enableBlikPaymentsCheckbox = $("#blikPayments");
	if (enableBlikPaymentsCheckbox) {
		enableBlikPaymentsCheckbox.addEventListener("click", () => {
			$("#blikPaymentsForm").hidden = !enableBlikPaymentsCheckbox.checked;
		});
	}
}

document.addEventListener("turbo:load", detectFeatures);
detectFeatures();
