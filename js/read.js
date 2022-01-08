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

function carouselRight(button) {
    let parents = button.parentElement.getElementsByTagName("h3");
    for (let i = 0; i < parents.length; i++) {
        let currentStyles = window.getComputedStyle(parents[i]);
        if ((currentStyles.display != "none") && (i < parents.length - 1)) {
            // Only works for two elements at the moment. Fix that.
            parents[i].style.display = "none";
            parents[i + 1].style.display = "block";
            break;
        } else {
            parents[i].style.display = "none";
            parents[i + 1].style.display = "none";
            parents[0].style.display = "block";
            break;
        }
    }
}

function carouselLeft(button) {
    let parents = button.parentElement.getElementsByTagName("h3");
    for (let i = 0; i < parents.length; i++) {
        let currentStyles = window.getComputedStyle(parents[i]);
        if ((currentStyles.display != "none") && (i < parents.length - 1)) {
            // Only works for two elements at the moment. Fix that.
            parents[i].style.display = "none";
            parents[i - 1].style.display = "block";
            break;
        } else {
            parents[0].style.display = "none";
            parents[i].style.display = "block";
            break;
        }
    }
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

// <div class="multiparents"><button carouselleft(this)'="">⮜</button><h3 style="display: block;"><a onclick="goTo('Q2N8NX.1')">Chapter 2: The Bohrok Swarms</a></h3><h3 style="display: none;"><a onclick="goTo('JBTY4O.1')">Chapter 3: The Toa Nuva &amp; Bohrok-Kal</a></h3><h3 style="display: none;"><a onclick="goTo('JBTY4O.1')">Chapter 4: Some Other Thing</a></h3><button onclick="carouselRight(this)">⮞</button></div>