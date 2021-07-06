function initialize() {
    if (localStorage.getItem("version") != "1.0") {
        localStorage.setItem("version", "1.0");
    }

    if (localStorage.getItem("languagePreference") === null) {
        // Get all language codes in use from DB.
        // Initially order according to volume.
        // If nav.lang in list, float to top.
        // Set.

        const lang = navigator.language;
        localStorage.setItem("languagePreference", lang.substring(0, 2));
    }

    if (localStorage.getItem("colorScheme") === null) {
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: light)").matches) {
            localStorage.setItem("colorScheme", "light");
        } else {
            localStorage.setItem("colorScheme", "dark");
        }
    }

    if (localStorage.getItem("readingOrder:0") === null || localStorage.getItem("version") === null) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("readingOrder:0", this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initread.php", true);
        xmlhttp.send();
    }

    if (localStorage.getItem("spoilerLevel") === null) {
        localStorage.setItem("spoilerLevel", "1");
    }
};

initialize();

function activateReadingOrder() {
    if (sessionStorage.getItem("activeReadingOrder") === null) {
        const keyArray = Object.keys(localStorage);
        let readingOrders = keyArray.filter(name => name.includes('readingOrder'));
        if (readingOrders.length == 1) {
            sessionStorage.setItem("activeReadingOrder", readingOrders[0]);
        } else {
            alert("Hmm…");
        }
    }
}

activateReadingOrder();

/* READER NAVIGATION */

function findSelf() {
    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    let index, result;
    const urlParams = new URLSearchParams(window.location.search);
    for (index = 0; index < readingOrder.length; index++) {
        if ((readingOrder[index].substring(0, readingOrder[index].indexOf(":"))) == urlParams.get('id')) {
            result = index;
        }
    }
    return (result);
}

function filteredSelf() {
    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    const urlParams = new URLSearchParams(window.location.search);

    let index;
    let goodValues = [];
    for (index = 0; index < readingOrder.length; index++) {
        value = readingOrder[index];
        if (value.substring(value.length - 2, value.length) === ":1") {
            goodValues.push(readingOrder[index]);
        }
    }

    let result;
    for (index = 0; index < goodValues.length; index++) {
        if ((goodValues[index].substring(0, goodValues[index].indexOf(":"))) == urlParams.get('id')) {
            result = index;
        }
    }
    return ([result, goodValues.length]);
}

function hideButtons() {
    if (filteredSelf()[0] === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (filteredSelf()[0] === (filteredSelf()[1]) - 1) {
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }

    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    if (findSelf() === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (findSelf() === readingOrder.length - 1) {
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }
}

hideButtons();

function jumpTo() {
    if (localStorage.getItem("savePlace") === null) {
        let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
        let index, value;
        for (index = 0; index < readingOrder.length; ++index) {
            value = readingOrder[index];
            if (value.substring(value.length - 2, value.length) === ":1") {
                result = value;
                window.location.href = ("/read/?id=" + readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
                break;
            }
        }
    } else {
        window.location.href = localStorage.getItem("savePlace");
    }
}

function goBack() {
    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    let currentNumber = findSelf();
    for (index = currentNumber - 1; index < readingOrder.length; index--) {
        if (readingOrder[index].includes(":1")) {
            window.location.href = ("/read/?id=" + readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
            break;
        }
    }
}

function goForward() {
    let readingOrder = localStorage.getItem(sessionStorage.getItem("activeReadingOrder")).split(",");
    let currentNumber = findSelf();
    for (index = currentNumber + 1; index < readingOrder.length; index++) {
        if (readingOrder[index].includes(":1")) {
            window.location.href = ("/read/?id=" + readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
            break;
        }
    }
}

function savePlace() {
    try {
        localStorage.setItem("savePlace", window.location);
        alert("Your place was saved successfully.")
    } catch {
        alert("Your place was not saved successfully. Please try clearing your cache and trying again.")
    }
}

function loadPlace() {
    if (localStorage.getItem("savePlace") === null) {
        jumpTo();
    } else {
        window.location.href = localStorage.getItem("savePlace");
    }
}
