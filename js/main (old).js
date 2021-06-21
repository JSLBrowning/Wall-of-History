// TO-DO: Read as standalone overrides normal reading order until user exits or finishes.
// if NOT ON page in tempReadingOrder, on LAST PAGE in temp, delete temp.
// Temp saveplace?
// Make navigation buttons hidden by default, appear when needed.
/*
NEEDS TO:
Set default color scheme, then match whatever's set.
Language?? Language is in cookies for most pages, goes from cookies to URL params for reader app.
Make active reading order... a session variable.
*/

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

function resetReadingOrder() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("readingOrder", this.responseText);
        }
    };
    xmlhttp.open("GET", "../php/initread.php", true);
    xmlhttp.send();

    alert("Your reading order has been reset.");
}

function downloadContent() {
    const urlParams = new URLSearchParams(window.location.search);
    let currentID = urlParams.get('id');

    $.get("/doc/downloads/" + currentID + ".zip")
        .done(function() {
            document.getElementById("downloadLink").href = "/doc/downloads/" + currentID + ".zip";
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("downloadLink").download = this.responseText.replace(/<\/?[^>]+(>|$)/g, "");
                }
            };
            xmlhttp.open("GET", "../php/gettitle.php?q=" + currentID, true);
            xmlhttp.send();
            document.getElementById("downloadLink").style.display = "block";
        }).fail(function() {
            console.log("No downloads are available for this content.");
        })
}

function findSelf() {
    let index, result;
    const urlParams = new URLSearchParams(window.location.search);

    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
        let readingOrder = localStorage.getItem("WallofHistoryTempReadingOrder").split(",");
        for (index = 0; index < readingOrder.length; index++) {
            if ((readingOrder[index].substring(0, readingOrder[index].indexOf(":"))) == urlParams.get('id')) {
                result = index;
                return (result);
            }
        }
    } else {
        let readingOrder = localStorage.getItem("readingOrder").split(",");
        for (index = 0; index < readingOrder.length; index++) {
            if ((readingOrder[index].substring(0, readingOrder[index].indexOf(":"))) == urlParams.get('id')) {
                result = index;
                return (result);
            }
        }
    }
}

function filteredSelf() {
    const urlParams = new URLSearchParams(window.location.search);

    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
        let readingOrder = localStorage.getItem("WallofHistoryTempReadingOrder").split(",");
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
                return ([result, goodValues.length]);
            }
        }
    } else {
        let readingOrder = localStorage.getItem("readingOrder").split(",");
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
                return ([result, goodValues.length]);
            }
        }
    }
}

function hideButtons() {
    if (filteredSelf()[0] === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (filteredSelf()[0] === (filteredSelf()[1]) - 1) {
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }

    let WoHReadingOrder = localStorage.getItem("readingOrder");
    let readingOrder = WoHReadingOrder.split(",");
    if (findSelf() === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (findSelf() === readingOrder.length - 1) {
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

function savePlace() {
    const urlParams = new URLSearchParams(window.location.search);
    try {
        if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
            localStorage.setItem("WallofHistoryTempSavePlace", window.location);
            alert("Your temporary place was saved successfully.")
        } else {
            localStorage.setItem("WallofHistorySavePlace", window.location);
            alert("Your place was saved successfully.")
        }
    } catch {
        alert("Your place was not saved successfully. Please try clearing your cache and trying again.")
    }
}

function jumpTo() {
    if (localStorage.getItem("WallofHistorySavePlace") === null) {
        let readingOrder = localStorage.getItem("readingOrder").split(",");
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
        window.location.href = localStorage.getItem("WallofHistorySavePlace");
    }
}

function loadPlace() {
    const urlParams = new URLSearchParams(window.location.search);
    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id"))) && (localStorage.getItem("WallofHistoryTempSavePlace") !== null)) {
        window.location.href = localStorage.getItem("WallofHistoryTempSavePlace");
    } else {
        if (localStorage.getItem("WallofHistorySavePlace") === null) {
            jumpTo();
        } else {
            window.location.href = localStorage.getItem("WallofHistorySavePlace");
        }
    }
}

function goBack() {
    let currentNumber = findSelf();

    // For temp reading orders.
    const urlParams = new URLSearchParams(window.location.search);
    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
        let readingOrder = localStorage.getItem("WallofHistoryTempReadingOrder").split(",");
        for (index = currentNumber - 1; index < readingOrder.length; index--) {
            if (readingOrder[index].includes(":1")) {
                window.location.href = ("/read/?id=" + readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
                break;
            }
            break;
        }
    } else {
        let readingOrder = localStorage.getItem("readingOrder").split(",");
        for (index = currentNumber - 1; index < readingOrder.length; index--) {
            if (readingOrder[index].includes(":1")) {
                window.location.href = ("/read/?id=" + readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
                break;
            }
        }
    }
}

function goForward() {
    let currentNumber = findSelf();

    const urlParams = new URLSearchParams(window.location.search);
    if ((localStorage.getItem("WallofHistoryTempReadingOrder") !== null) && (localStorage.getItem("WallofHistoryTempReadingOrder").includes(urlParams.get("id")))) {
        let readingOrder = localStorage.getItem("WallofHistoryTempReadingOrder").split(",");
        for (index = currentNumber + 1; index < readingOrder.length; index++) {
            if (readingOrder[index].includes(":1")) {
                window.location.href = ("/read/?id=" + readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
                break;
            }
            break;
        }
    } else if ((localStorage.getItem("readingOrder") == null)) {
        console.log("Reading order is null. This is not supposed to be possible.");
    } else {
        let readingOrder = localStorage.getItem("readingOrder").split(",");
        for (index = currentNumber + 1; index < readingOrder.length; index++) {
            if (readingOrder[index].includes(":1")) {
                window.location.href = ("/read/?id=" + readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
                break;
            }
        }
    }
}

/*
On-site read button will always prompt for reading order selection (if multiple reading orders).
If only one, always make active.
If on reader without active, prompt for active choice when buttons are pressed.
Progress bars? Save places by ID and active reading order?

MASTER RESET BUTTON ON SETTINGS PAGE!!
*/

function getTempReadingOrder() {
    return new Promise(resolve => {
        let currentID = new URLSearchParams(window.location.search).get('id');
        console.log(currentID);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("WallofHistoryTempReadingOrder", this.responseText);
                resolve(1);
            } else {
                resolve(0);
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
    } else {
        alert("We're sorry, something went wrong. Please contact the website administrator.");
    }
}