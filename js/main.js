// TO-DO: Read as standalone overrides normal reading order until user exits or finishes.
// if NOT ON page in tempReadingOrder, on LAST PAGE in temp, delete temp.
// Temp saveplace?
// Make navigation buttons hidden by default, appear when needed.

function MODRebrand() {
    if (window.location !== window.parent.location) {
        var head = document.getElementsByTagName('head')[0];
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = 'https://wallofhistory.com/css/mod.css';
        head.appendChild(link);

        let headerimg = document.getElementsByTagName('header')[0].getElementsByTagName('img')[0];
        headerimg.remove();
    }
}

MODRebrand();

function clearStandalone() {
    if ((localStorage.getItem("WallofHistoryTempReadingOrder")) !== null) {
        console.log("A")
        let tempReadingOrder = localStorage.getItem("WallofHistoryTempReadingOrder");
        console.log(tempReadingOrder);
        const urlParams = new URLSearchParams(window.location.search);
        if (tempReadingOrder.includes(urlParams.get('id'))) {
            null;
        } else {
            localStorage.clear("WallofHistoryTempReadingOrder");
            console.log("Cleared.");
        }
    }
}

clearStandalone();

function initRead() {
    if (localStorage.getItem("WallofHistoryReadingOrder") === null || localStorage.getItem("WallofHistoryReadingOrderApplicationDate") != "08102020") {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("WallofHistoryReadingOrder", this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initread.php", true);
        xmlhttp.send();
    }

    if (localStorage.getItem("WallofHistoryLanguageList") === null) {
        localStorage.setItem("WallofHistoryLanguageList", "en,es");
        localStorage.setItem("WallofHistoryReadingOrderApplicationDate", "08102020");
    }
}

initRead();

function resetReadingOrder() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("WallofHistoryReadingOrder", this.responseText);
        }
    };
    xmlhttp.open("GET", "../php/initread.php", true);
    xmlhttp.send();

    alert("Your reading order has been reset.");
}

function findSelf() {
    let index, result;
    const urlParams = new URLSearchParams(window.location.search);

    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryTempReadingOrder").split(",");
        for (index = 0; index < WallofHistoryReadingOrder.length; index++) {
            if ((WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":"))) == urlParams.get('id')) {
                result = index;
            }
        }
    } else {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryReadingOrder").split(",");
        for (index = 0; index < WallofHistoryReadingOrder.length; index++) {
            if ((WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":"))) == urlParams.get('id')) {
                result = index;
            }
        }
    }
    return (result);
}

function filteredSelf() {
    const urlParams = new URLSearchParams(window.location.search);

    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryTempReadingOrder").split(",");
        let index;
        let goodValues = [];
        for (index = 0; index < WallofHistoryReadingOrder.length; index++) {
            value = WallofHistoryReadingOrder[index];
            if (value.substring(value.length - 2, value.length) === ":1") {
                goodValues.push(WallofHistoryReadingOrder[index]);
            }
        }

        let result;
        for (index = 0; index < goodValues.length; index++) {
            if ((goodValues[index].substring(0, goodValues[index].indexOf(":"))) == urlParams.get('id')) {
                result = index;
            }
        }
    } else {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryReadingOrder").split(",");
        let index;
        let goodValues = [];
        for (index = 0; index < WallofHistoryReadingOrder.length; index++) {
            value = WallofHistoryReadingOrder[index];
            if (value.substring(value.length - 2, value.length) === ":1") {
                goodValues.push(WallofHistoryReadingOrder[index]);
            }
        }

        let result;
        for (index = 0; index < goodValues.length; index++) {
            if ((goodValues[index].substring(0, goodValues[index].indexOf(":"))) == urlParams.get('id')) {
                result = index;
            }
        }
    }
    return ([result, goodValues.length]);
}

function hideButtons() {
    initRead();
    if (filteredSelf()[0] === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (filteredSelf()[0] === (filteredSelf()[1]) - 1) {
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }

    let WoHReadingOrder = localStorage.getItem("WallofHistoryReadingOrder");
    let WallofHistoryReadingOrder = WoHReadingOrder.split(",");
    if (findSelf() === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (findSelf() === WallofHistoryReadingOrder.length - 1) {
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }

    const urlParams = new URLSearchParams(window.location.search);
    let currentId = urlParams.get("id");

    if (!(WoHReadingOrder.indexOf(currentId) !== -1)) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }
}

hideButtons();

function jumpTo() {
    if (localStorage.getItem("WallofHistorySavePlace") === null) {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryReadingOrder").split(",");
        let index, value;
        for (index = 0; index < WallofHistoryReadingOrder.length; ++index) {
            value = WallofHistoryReadingOrder[index];
            if (value.substring(value.length - 2, value.length) === ":1") {
                result = value;
                window.location.href = ("/read/?id=" + WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":")));
                break;
            }
        }
    } else {
        window.location.href = localStorage.getItem("WallofHistorySavePlace");
    }
}

function goBack() {
    let currentNumber = findSelf();

    // For temp reading orders.
    const urlParams = new URLSearchParams(window.location.search);
    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryTempReadingOrder").split(",");
        for (index = currentNumber - 1; index < WallofHistoryReadingOrder.length; index--) {
            if (WallofHistoryReadingOrder[index].includes(":1")) {
                window.location.href = ("/read/?id=" + WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":")));
                break;
            }
            break;
        }
    } else {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryReadingOrder").split(",");
        for (index = currentNumber - 1; index < WallofHistoryReadingOrder.length; index--) {
            if (WallofHistoryReadingOrder[index].includes(":1")) {
                window.location.href = ("/read/?id=" + WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":")));
                break;
            }
            break;
        }
    }
}

function goForward() {
    let currentNumber = findSelf();

    const urlParams = new URLSearchParams(window.location.search);
    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryTempReadingOrder").split(",");
        for (index = currentNumber + 1; index < WallofHistoryReadingOrder.length; index++) {
            console.log(index);
            if (WallofHistoryReadingOrder[index].includes(":1")) {
                window.location.href = ("/read/?id=" + WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":")));
                break;
            }
            break;
        }
    } else {
        let WallofHistoryReadingOrder = localStorage.getItem("WallofHistoryReadingOrder").split(",");
        for (index = currentNumber + 1; index < WallofHistoryReadingOrder.length; index++) {
            if (WallofHistoryReadingOrder[index].includes(":1")) {
                window.location.href = ("/read/?id=" + WallofHistoryReadingOrder[index].substring(0, WallofHistoryReadingOrder[index].indexOf(":")));
                break;
            }
            break;
        }
    }
}

function savePlace() {
    try {
        localStorage.setItem("WallofHistorySavePlace", window.location);
        alert("Your place was saved successfully.")
    } catch {
        alert("Your place was not saved successfully. Please try clearing your cache and trying again.")
    }
}

function loadPlace() {
    if (localStorage.getItem("WallofHistorySavePlace") === null) {
        jumpTo();
    } else {
        window.location.href = localStorage.getItem("WallofHistorySavePlace");
    }
}

function getParent() {
    const urlParams = new URLSearchParams(window.location.search);
    let currentID = urlParams.get('id');

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            window.location.href = ("/read/?id=" + this.responseText);
        }
    };
    xmlhttp.open("GET", "../php/getparent.php?q=" + currentID, true);
    xmlhttp.send();
}

function downloadContent() {
    const urlParams = new URLSearchParams(window.location.search);
    let currentID = urlParams.get('id');

    $.get("/doc/downloads/" + currentID + ".zip")
        .done(function () {
            document.getElementById("downloadLink").href = "/doc/downloads/" + currentID + ".zip";
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("downloadLink").download = this.responseText.replace(/<\/?[^>]+(>|$)/g, "");
                }
            };
            xmlhttp.open("GET", "../php/gettitle.php?q=" + currentID, true);
            xmlhttp.send();
            document.getElementById("downloadLink").style.display = "block";
        }).fail(function () {
            console.log("No downloads available for this content.");
        })
}

function getTempReadingOrder() {
    return new Promise(resolve => {
        console.log("Success 1.");
        let currentID = new URLSearchParams(window.location.search).get('id');
        console.log(currentID);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("WallofHistoryTempReadingOrder", this.responseText);
                resolve(1);
            }
        };
        xmlhttp.open("GET", "../php/initreadstandalone.php?q=" + currentID, true);
        xmlhttp.send();
    })
}

async function readStandalone() {
    let x = await getTempReadingOrder();
    if (x = 1) {
        let tempReadingOrder = localStorage.getItem("WallofHistoryTempReadingOrder");
        window.location.href = "/read/?id=" + tempReadingOrder.split(':')[0];
    }
}
