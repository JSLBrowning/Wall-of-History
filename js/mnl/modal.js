// MENU MODAL
var allModals = document.getElementsByClassName("modal");
var navigationButton = document.getElementById("navigationButton");
var navigationModal = document.getElementById("navigationModal");
var navigationClose = document.getElementById("navigationClose");

navigationButton.onclick = function() {
    navigationModal.style.display = "block";
}

navigationClose.onclick = function() {
    navigationModal.style.display = "none";
}

// This works for the second modal, but not the first... Why?
// Well, now it doesn't work for either of them. Great.
// Wait, it works for... the first one, on the homepage.
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        for (i = 0; i < allModals.length; i++) {
            allModals[i].style.display = "none";
        }
    }
}