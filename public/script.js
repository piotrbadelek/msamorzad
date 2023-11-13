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
	};
};

function calculateTotalAmount() {
	$("#totalAmount").innerText = `Łącznie: ${$("#money").value * $("[data-total-students]").dataset.totalStudents} zł`
}

const requestNotificationPermission = async () => {
	const permission = await Notification.requestPermission();
	if (permission !== "granted") {
		throw new Error('Permission not granted for Notification');
	}
}

function enableNotifications() {
	const permission =  requestNotificationPermission();
	console.log("Notification persmission: " + permission);
	$("#notificationDialogue").open = false;
	store("notificationReminderStartups", "9999");
}

function disableNotifications() {
	store("notificationReminderStartups", "10");
	$("#notificationDialogue").open = false;
}

if (Notification.permission !== "granted" && +store("notificationReminderStartups") < 1) {
	$("#notificationDialogue").open = true;
}

if (+store("notificationReminderStartups") !== 9999) {
	store("notificationReminderStartups", `${+store("notificationReminderStartups") - 1}`);
}
