function swap(swappableID) {
    let swappableDiv = document.getElementById(swappableID);
    let swappables = swappableDiv.children;
    for (let i = 0; i < swappables.length; i++) {
        let currentStyles = window.getComputedStyle(swappables[i]);
        if (currentStyles.display === "none" || currentStyles.display === "") {
            // swappables[i].style.display = "block";
            setTimeout(() => {
                $(swappables[i]).slideDown();
            }, 400);
        } else {
            // swappables[i].style.display = "none";
            $(swappables[i]).slideUp();
        }
    }

    let thisButton = swappableDiv.nextElementSibling;
    let labelOld = thisButton.innerText;
    let labelNew = thisButton.getAttribute('data-alternate');
    thisButton.innerText = labelNew;
    thisButton.dataset.alternate = labelOld;
}

function check() {
    if (sessionStorage.getItem("activeReadingOrder") === null && Object.keys(localStorage).filter(name => name.includes('readingOrder')).length > 1) {
        generateSelectionModal();
    } else if (sessionStorage.getItem("activeReadingOrder") === null) {
        sessionStorage.setItem("activeReadingOrder", "0");
    }
    // If not on reader... empty sessionStorage.
}

let checkInterval = window.setInterval(check(), 500);