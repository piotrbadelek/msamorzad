"use strict";

function $(selector) {
    return document.querySelector(selector);
}

function calculateTotalAmount() {
    $("#totalAmount").innerText = `Łącznie: ${$("#money").value * $("[data-total-students]").dataset.totalStudents} zł`
}
