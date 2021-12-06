function toggleModal(id) {
    let targetModal = document.getElementById(id);
    let targetModalContent = document.getElementById(id).children[0];
    if (targetModal.style.visibility == "hidden" || targetModal.style.visibility == "") {
        targetModal.style.backgroundColor = "rgba(0,0,0,0.75)";
        targetModalContent.classList.add("modal-content-left-visible");
        targetModal.style.visibility = "visible";
        targetModal.style.zIndex = "3";
    } else {
        setTimeout(function() { targetModal.style.visibility = "hidden" }, 500);
        targetModalContent.classList.remove("modal-content-left-visible");
        targetModal.style.backgroundColor = "rgba(0,0,0,0)";
        targetModal.style.zIndex = "0";
    }
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        let modals = document.getElementsByClassName("modal");
        for (let i = 0; i < modals.length; i++) {
            setTimeout(function() { modals[i].style.visibility = "hidden" }, 500);
            modals[i].children[0].classList.remove("modal-content-left-visible");
            modals[i].style.backgroundColor = "rgba(0,0,0,0)";
            modals[i].style.zIndex = "0";
        }
    }
}