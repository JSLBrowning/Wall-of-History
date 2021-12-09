function toggleModal(id) {
    console.log("1");
    let targetModal = document.getElementById(id);
    let targetModalContent = document.getElementById(id).children[0];
    let modalStyle = window.getComputedStyle(targetModal);
    let modalVisibility = modalStyle.getPropertyValue("visibility");
    if (modalVisibility == "hidden") {
        targetModal.classList.add("modal-visible");
        if (targetModalContent.classList.contains("modal-content-left")) {
            targetModalContent.classList.add("modal-content-left-visible");
        } else if (targetModalContent.classList.contains("modal-content-right")) {
            targetModalContent.classList.add("modal-content-right-visible");
        }
    } else {
        targetModal.classList.remove("modal-visible");
        if (targetModalContent.classList.contains("modal-content-left-visible")) {
            targetModalContent.classList.remove("modal-content-left-visible");
        } else if (targetModalContent.classList.contains("modal-content-right-visible")) {
            targetModalContent.classList.remove("modal-content-right-visible");
        }
    }
}

function generalToggle() {
    let modals = document.getElementsByClassName("modal");
    for (let i = 0; i < modals.length; i++) {
        modals[i].classList.remove("modal-visible");
        modals[i].children[0].classList.remove("modal-content-left-visible");
        if (modals[i].children[0].classList.contains("modal-content-left-visible")) {
            modals[i].children[0].classList.remove("modal-content-left-visible");
        } else if (modals[i].children[0].classList.contains("modal-content-right-visible")) {
            modals[i].children[0].classList.remove("modal-content-right-visible");
        } else if (modals[i].children[0].classList.contains("modal-content-center-visible")) {
            modals[i].children[0].classList.remove("modal-content-center-visible");
        }
    }
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        generalToggle();
    }
}

document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        generalToggle();
    }
});

function getModalContent(identifier) {
    // FIND MODAL
    let targetModal = document.getElementById("myModal");
    let targetModalContent = document.getElementById("myModal").children[0];
    let modalStyle = window.getComputedStyle(targetModal);
    let modalVisibility = modalStyle.getPropertyValue("visibility");

    // FETCH MODAL CONTENT
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("modal-data").innerHTML = this.responseText;

            spoilerlevel = parseInt(localStorage.getItem("WallofHistorySpoilerLevel"));
            children = document.getElementById("modal-data").children;
            for (i = 0; i < children.length; i++) {
                if (children[i].hasAttribute("data-spoiler")) {
                    if (parseInt(children[i].getAttribute("data-spoiler")) > spoilerlevel) {
                        children[i].style.display = "none";
                    }
                }
            }
        }
    };
    xmlhttp.open("GET", "../php/getmodaldata.php?q=" + identifier, true);
    xmlhttp.send();

    if (modalVisibility == "hidden") {
        targetModal.classList.add("modal-visible");
        targetModalContent.classList.add("modal-content-center-visible");
    } else {
        targetModal.classList.remove("modal-visible");
        targetModalContent.classList.remove("modal-content-center-visible");
    }
}