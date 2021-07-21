async function initialize() {
    if (localStorage.getItem("version") != "1.0") {
        // Try to convert saved place first.
        localStorage.setItem("version", "1.0");
    }

    if ((localStorage.getItem("languagePreference") === null) || (localStorage.getItem("languageList") === null)) {
        // Step 1: Get preferred language.
        const lang = navigator.language;
        localStorage.setItem("languagePreference", lang.substring(0, 2));

        // Step 2: Get all languages and put them in a list.
        languageList = await getLanguageList();
        localStorage.setItem("languageList", languageList);
    }

    // Step 3: If preferred language is in language list, bring it to the front.
    if (localStorage.getItem("languageList").includes(localStorage.getItem("languagePreference"))) {
        let languageList = localStorage.getItem("languageList").split(",");
        let preferred = localStorage.getItem("languagePreference");
        languageList.sort(function(x, y) { return x == preferred ? -1 : y == preferred ? 1 : 0; });
        localStorage.setItem("languageList", languageList);
    }

    if (localStorage.getItem("colorScheme") === null) {
        if (window.matchMedia && window.matchMedia("(prefers-color-scheme: light)").matches) {
            localStorage.setItem("colorScheme", "light");
        } else {
            localStorage.setItem("colorScheme", "dark");
        }
    }

    if (localStorage.getItem("readingOrder:0") === null || localStorage.getItem("version") === null) {
        let recommendedReadingOrder = await getRecommendedReadingOrder();
        localStorage.setItem("readingOrder:0", recommendedReadingOrder);
    }

    if (sessionStorage.getItem("activeReadingOrder") === null) {
        const keyArray = Object.keys(localStorage);
        let readingOrders = keyArray.filter(name => name.includes('readingOrder'));
        if (readingOrders.length == 1) {
            sessionStorage.setItem("activeReadingOrder", readingOrders[0].split(":")[1]);
        }
    }

    if (localStorage.getItem("spoilerLevel") === null) {
        localStorage.setItem("spoilerLevel", "1");
    }

    if (localStorage.getItem("referenceTerms") === null) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                localStorage.setItem("referenceTerms", this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/getreferenceterms.php", true);
        xmlhttp.send();
    }
};

function getLanguageList() {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/getlanguagelist.php", true);
        xmlhttp.send();
    });
}

function getRecommendedReadingOrder() {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initread.php", true);
        xmlhttp.send();
    });
}

initialize();

function resetReader() {
    localStorage.clear();
    initialize();
    alert("Reset complete.");
}

/* READER NAVIGATION HELPERS */

function getOptimalLanguage(combo) {
    return new Promise(resolve => {
        let languageList = localStorage.getItem("languageList").split(",");
        let target = combo.split(".");
        let id = target[0];
        let v = target[1];
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                availableLanguages = this.responseText.split(",");

                let a = [];
                for (i = 0; i < availableLanguages.length; i++) {
                    a.push(availableLanguages[i]);
                }

                let intersection = languageList.filter(x => a.includes(x));
                resolve(intersection[0]);
            }
        };
        xmlhttp.open("GET", "../php/getavailablelanguages.php?id=" + id + "&v=" + v, true);
        xmlhttp.send();
    });
}

function findSelf() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
    let index, result;
    const urlParams = new URLSearchParams(window.location.search);
    for (index = 0; index < readingOrder.length; index++) {
        let thing = readingOrder[index].substring(0, readingOrder[index].indexOf(":")).split(".")[0];
        if (thing == urlParams.get('id')) {
            result = index;
        }
    }
    return (result);
}

function filteredSelf() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
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

    // There has to be a better way to do this.
    let readingOrder = localStorage.getItem("readingOrder:0");
    if (findSelf() === 0) {
        let backButton = document.getElementById("backbutton");
        backButton.style.display = "none";
    } else if (findSelf() === readingOrder.length - 1) {
        let forwardButton = document.getElementById("forwardbutton");
        forwardButton.style.display = "none";
    }
}

hideButtons();

/* READER NAVIGATION */

async function goTo(combo) {
    target = combo.split(".");
    let id = target[0];
    let v = target[1];
    let lang = await getOptimalLanguage(combo);
    window.location.href = ("/read/?id=" + id + "&v=" + v + "&lang=" + lang);
}

async function jumpTo() {
    if (sessionStorage.getItem("activeReadingOrder") === null) {
        generateSelectionModal();
    } else if ((sessionStorage.getItem("activeReadingOrder") != null) && (localStorage.getItem("savePlace:" + (sessionStorage.getItem("activeReadingOrder"))) === null)) {
        let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
        let index, value;
        for (index = 0; index < readingOrder.length; ++index) {
            value = readingOrder[index];
            if (value.substring(value.length - 2, value.length) === ":1") {
                result = value;
                newID = readingOrder[index].substring(0, readingOrder[index].indexOf(":")).split(".")[0];
                newVersion = readingOrder[index].substring(0, readingOrder[index].indexOf(":")).split(".")[1];
                newLang = await getOptimalLanguage(readingOrder[index].substring(0, readingOrder[index].indexOf(":")));
                window.location.href = ("/read/?id=" + newID + "&v=" + newVersion + "&lang=" + newLang);
                break;
            }
        }
    } else {
        window.location.href = localStorage.getItem("savePlace:" + (sessionStorage.getItem("activeReadingOrder")));
    }
}

async function goBack() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
    let currentNumber = findSelf();
    for (index = currentNumber - 1; index < readingOrder.length; index--) {
        if (readingOrder[index].includes(":1")) {
            next = readingOrder[index].split(":")[0];
            goTo(next);
            break;
        }
    }
}

async function goForward() {
    let readingOrder = localStorage.getItem("readingOrder:" + sessionStorage.getItem("activeReadingOrder")).split(",");
    let currentNumber = findSelf();
    for (index = currentNumber + 1; index < readingOrder.length; index++) {
        if (readingOrder[index].includes(":1")) {
            next = readingOrder[index].split(":")[0];
            goTo(next);
            break;
        }
    }
}

function savePlace() {
    try {
        localStorage.setItem("savePlace:" + sessionStorage.getItem("activeReadingOrder"), window.location);
        alert("Your place was saved successfully.")
    } catch {
        alert("Your place was not saved successfully. Please try clearing your cache and trying again.")
    }
}

function loadPlace() {
    if (localStorage.getItem("savePlace:" + sessionStorage.getItem("activeReadingOrder")) === null) {
        jumpTo();
    } else {
        window.location.href = localStorage.getItem("savePlace:" + sessionStorage.getItem("activeReadingOrder"));
    }
}

/* READ AS STANDALONE */

function readAsStandaloneSetup(id) {
    return new Promise(resolve => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttp.open("GET", "../php/initreadstandalone.php?id=" + id + "&v=1", true);
        xmlhttp.send();
    });
}

async function readAsStandalone() {
    let currentID = new URLSearchParams(window.location.search).get('id');
    let newOrder = await readAsStandaloneSetup(currentID);
    localStorage.setItem("readingOrder:" + currentID, newOrder);
    sessionStorage.setItem("activeReadingOrder", currentID);
    jumpTo();
}

// For each ID, only make one version (in each language) the "recommended" one, and that'll be the one that gets put in the recommended reading order.
// Make items with multiple versions have dropdown menus in the sort thing?
// For read as standalone... make versions map the web.

// On settings page: if more than one version and recommended, check recommended version. Else... else.

// localStorage.setItem("WallofHistorySpoilerLevel", $("section").data("spoiler"));

// Children on tables of contents need a version matching the parent, so comics aren't displayed multiple times.
// Chapters may get weird here — a GN itself would need to be version 1, for example, so it'll display in the appropriate spot on the ToC, but the chapters of it need to be version 2.